<?php
/**
 * お問い合わせフォームの送信
 *
 * 守っていること
 * - 正しいフォームから来たか(nonce)
 * - 機械的な送信の除外(人には見えない入力欄が埋まっていたら捨てる／開いてから3秒未満の送信を捨てる)
 * - 同じ人からの連続送信は60秒あける
 * - メールの見出しに改行を入れさせない(名前・アドレスは1行の文字に整える)
 * - 届いた内容は「お問い合わせ履歴」にも保存する(メールが届かなかったときの控え)
 */

defined( 'ABSPATH' ) || exit;

/** ご依頼内容の選択肢 */
function hn_contact_kinds() {
	return array(
		'舞台・朗読 出演',
		'映像・短編映画・MV 出演',
		'モデル・撮影・ルックブック',
		'イベント・トーク 出演',
		'その他',
	);
}

add_action( 'admin_post_nopriv_hn_contact', 'hn_handle_contact' );
add_action( 'admin_post_hn_contact', 'hn_handle_contact' );

function hn_handle_contact() {
	$back = hn_url( 'contact' );
	$fail = function ( $code ) use ( $back ) {
		wp_safe_redirect( add_query_arg( 'hn_error', $code, $back ) . '#contact-form' );
		exit;
	};

	if ( ! isset( $_POST['hn_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hn_contact_nonce'] ) ), 'hn_contact' ) ) {
		$fail( 'expired' );
	}

	// 人には見えない欄が埋まっている/早すぎる送信は、成功したように見せて捨てる
	$trap = isset( $_POST['hn_website'] ) ? trim( wp_unslash( $_POST['hn_website'] ) ) : '';
	$ts   = isset( $_POST['hn_ts'] ) ? (int) $_POST['hn_ts'] : 0;
	if ( '' !== $trap || ( $ts && time() - $ts < 3 ) ) {
		wp_safe_redirect( add_query_arg( 'hn_sent', '1', $back ) . '#contact-form' );
		exit;
	}

	$name    = isset( $_POST['hn_name'] ) ? sanitize_text_field( wp_unslash( $_POST['hn_name'] ) ) : '';
	$email   = isset( $_POST['hn_email'] ) ? sanitize_email( wp_unslash( $_POST['hn_email'] ) ) : '';
	$kind    = isset( $_POST['hn_kind'] ) ? sanitize_text_field( wp_unslash( $_POST['hn_kind'] ) ) : '';
	$message = isset( $_POST['hn_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hn_message'] ) ) : '';

	if ( '' === $name || '' === $message || ! is_email( $email ) ) {
		$fail( 'required' );
	}
	if ( mb_strlen( $name ) > 100 || mb_strlen( $message ) > 5000 ) {
		$fail( 'too_long' );
	}
	if ( ! in_array( $kind, hn_contact_kinds(), true ) ) {
		$kind = 'その他';
	}

	// 連続送信の制限(IPアドレスはそのまま保存せず、ハッシュにする)
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'hn_contact_' . md5( $ip . wp_salt() );
	if ( get_transient( $key ) ) {
		$fail( 'too_fast' );
	}
	set_transient( $key, 1, 60 );

	$body = "はぎのりな公式サイトのお問い合わせフォームから届きました。\n\n"
		. "お名前: {$name}\n"
		. "メールアドレス: {$email}\n"
		. "ご依頼内容: {$kind}\n\n"
		. "メッセージ:\n{$message}\n\n"
		. "----\n送信日時: " . wp_date( 'Y-m-d H:i' ) . "\n"
		. "このメールに返信すると、送り主に届きます。\n";

	// 控えを保存
	wp_insert_post(
		array(
			'post_type'    => 'hn_inquiry',
			'post_status'  => 'private',
			'post_title'   => wp_date( 'Y-m-d H:i' ) . ' ' . $name . '(' . $kind . ')',
			'post_content' => $body,
		)
	);

	$headers = array( 'Reply-To: ' . str_replace( array( "\r", "\n", '<', '>' ), '', $name ) . ' <' . $email . '>' );
	$sent    = wp_mail( hn_contact_to(), '【はぎのりな公式サイト】お問い合わせ：' . $kind, $body, $headers );

	if ( ! $sent ) {
		// メールが出なくても控えは残っているので、送った人には受け付けたと伝える
		error_log( '[haginorina] wp_mail failed for contact form' );
	}
	wp_safe_redirect( add_query_arg( 'hn_sent', '1', $back ) . '#contact-form' );
	exit;
}

/** フォームの上に出すお知らせ */
function hn_contact_notice() {
	if ( isset( $_GET['hn_sent'] ) ) {
		return array( 'ok', '送信しました。内容を確認のうえ、3〜7日以内にご返信いたします。' );
	}
	if ( isset( $_GET['hn_error'] ) ) {
		$msgs = array(
			'required' => 'お名前・メールアドレス・メッセージを入力してください。',
			'too_long' => '文字数が多すぎます。メッセージは5000文字までにしてください。',
			'too_fast' => '続けて送信されました。1分ほどあけてから、もう一度お試しください。',
			'expired'  => 'ページの有効期限が切れました。お手数ですが、もう一度入力してください。',
		);
		$code = sanitize_key( wp_unslash( $_GET['hn_error'] ) );
		return array( 'error', isset( $msgs[ $code ] ) ? $msgs[ $code ] : '送信できませんでした。もう一度お試しください。' );
	}
	return null;
}
