<?php
/**
 * 入力欄(メタボックス)
 *
 * 項目の定義を配列に持ち、表示と保存を共通の処理で行う。
 * 項目を増やすときは hn_meta_fields() に1行足す。
 */

defined( 'ABSPATH' ) || exit;

/** 写真の「顔の位置」の選択肢(object-position の値) */
function hn_focus_choices() {
	return array(
		'50% 30%'  => '真ん中・やや上(ふつうはこれ)',
		'50% 50%'  => '真ん中',
		'50% 10%'  => '真ん中・上',
		'50% 80%'  => '真ん中・下',
		'20% 30%'  => '左寄り',
		'80% 30%'  => '右寄り',
		'0% 30%'   => 'いちばん左',
		'100% 30%' => 'いちばん右',
	);
}

function hn_meta_fields() {
	return array(
		'hn_schedule' => array(
			'title'  => '公演の情報',
			'fields' => array(
				'_hn_start' => array( 'label' => '初日', 'type' => 'date', 'help' => 'トップの「次の舞台」には、まだ終わっていない公演のうち一番近いものが自動で出ます。' ),
				'_hn_end'   => array( 'label' => '千秋楽(1日だけなら空欄)', 'type' => 'date' ),
				'_hn_when'  => array( 'label' => '日程の表示(日にちが未定のとき)', 'type' => 'text', 'placeholder' => '2027年2月', 'help' => '入れると、日付の代わりにこの文字を表示します。並び順と「終わったか」の判定は、上の日付で行います。' ),
				'_hn_venue' => array( 'label' => '会場', 'type' => 'text', 'placeholder' => '新宿村LIVE' ),
				'_hn_part'  => array( 'label' => '関わり方', 'type' => 'text', 'placeholder' => '全ステージ出演 / ゲスト出演 / 演出 など' ),
				'_hn_url'   => array( 'label' => 'リンク先(お知らせ記事や公式サイトのURL)', 'type' => 'url', 'placeholder' => 'https://' ),
			),
		),
		'hn_role'     => array(
			'title'  => 'カードの中身',
			'fields' => array(
				'_hn_sub'    => array( 'label' => '役名の下の小さい文字', 'type' => 'text', 'placeholder' => '2026', 'help' => 'カードの表面は「写真(アイキャッチ)」と「タイトル=役名」です。' ),
				'_hn_kind'   => array( 'label' => '裏面の見出し', 'type' => 'text', 'placeholder' => 'ROLE' ),
				'_hn_work'   => array( 'label' => '作品名', 'type' => 'text', 'placeholder' => '「グノーシア ザ・ライブプレイングシアター」' ),
				'_hn_l1'     => array( 'label' => '裏面1行目の見出し', 'type' => 'text', 'placeholder' => '期間' ),
				'_hn_period' => array( 'label' => '裏面1行目', 'type' => 'text', 'placeholder' => '2026.8.21 – 9.6' ),
				'_hn_l2'     => array( 'label' => '裏面2行目の見出し', 'type' => 'text', 'placeholder' => '劇場' ),
				'_hn_venue'  => array( 'label' => '裏面2行目', 'type' => 'text', 'placeholder' => '飛行船シアター' ),
				'_hn_focus'  => array( 'label' => '顔の位置', 'type' => 'focus' ),
			),
		),
		'post'        => array(
			'title'  => 'アイキャッチ写真の顔の位置',
			'fields' => array(
				'_hn_focus' => array( 'label' => '顔の位置', 'type' => 'focus', 'help' => '記事ページの上の写真で顔が切れるときに変えてください。' ),
			),
		),
		'page'        => array(
			'title'     => '活動紹介の一覧(1行に1項目。空欄なら今の内容のまま)',
			'only_slug' => 'works',
			'fields'    => array(
				'_hn_list_stage' => array( 'label' => '舞台の「主な出演作」', 'type' => 'textarea', 'placeholder' => '「作品名」役名(2026)' ),
				'_hn_list_film'  => array( 'label' => '映像の「主な出演作」', 'type' => 'textarea' ),
				'_hn_list_model' => array( 'label' => 'モデルの「撮影の内容」', 'type' => 'textarea' ),
			),
		),
		'hn_photo'    => array(
			'title'  => '写真の設定',
			'fields' => array(
				'_hn_caption' => array( 'label' => '写真を大きく開いたときの説明(空欄でも可)', 'type' => 'text' ),
				'_hn_focus'   => array( 'label' => '顔の位置', 'type' => 'focus', 'help' => '一覧で顔が切れるときに変えてください。' ),
			),
		),
	);
}

add_action( 'add_meta_boxes', function ( $post_type, $post ) {
	foreach ( hn_meta_fields() as $pt => $box ) {
		if ( ! empty( $box['only_slug'] ) && ( ! $post || $post->post_name !== $box['only_slug'] ) ) {
			continue;
		}
		add_meta_box( 'hn_meta_' . $pt, $box['title'], 'hn_render_meta_box', $pt, 'post' === $pt ? 'side' : 'normal', 'post' === $pt ? 'default' : 'high', array( 'fields' => $box['fields'] ) );
	}
}, 10, 2 );

function hn_render_meta_box( $post, $box ) {
	wp_nonce_field( 'hn_meta_save', 'hn_meta_nonce' );
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $box['args']['fields'] as $key => $f ) {
		$val = get_post_meta( $post->ID, $key, true );
		$id  = esc_attr( ltrim( $key, '_' ) );
		echo '<tr><th scope="row"><label for="' . $id . '">' . esc_html( $f['label'] ) . '</label></th><td>';
		if ( 'focus' === $f['type'] ) {
			$val = $val ? $val : '50% 30%';
			echo '<select id="' . $id . '" name="' . esc_attr( $key ) . '">';
			foreach ( hn_focus_choices() as $v => $label ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $v ), selected( $val, $v, false ), esc_html( $label ) );
			}
			if ( ! isset( hn_focus_choices()[ $val ] ) ) {
				printf( '<option value="%s" selected>%s(個別に調整した値)</option>', esc_attr( $val ), esc_html( $val ) );
			}
			echo '</select>';
		} elseif ( 'textarea' === $f['type'] ) {
			printf(
				'<textarea id="%s" name="%s" rows="7" class="large-text" placeholder="%s">%s</textarea>',
				$id,
				esc_attr( $key ),
				esc_attr( isset( $f['placeholder'] ) ? $f['placeholder'] : '' ),
				esc_textarea( $val )
			);
		} else {
			$type = in_array( $f['type'], array( 'date', 'url' ), true ) ? $f['type'] : 'text';
			printf(
				'<input type="%s" id="%s" name="%s" value="%s" class="regular-text" placeholder="%s" />',
				esc_attr( $type ),
				$id,
				esc_attr( $key ),
				esc_attr( $val ),
				esc_attr( isset( $f['placeholder'] ) ? $f['placeholder'] : '' )
			);
		}
		if ( ! empty( $f['help'] ) ) {
			echo '<p class="description">' . esc_html( $f['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

add_action( 'save_post', function ( $post_id, $post ) {
	$defs = hn_meta_fields();
	if ( ! isset( $defs[ $post->post_type ] ) ) {
		return;
	}
	if ( ! empty( $defs[ $post->post_type ]['only_slug'] ) && $post->post_name !== $defs[ $post->post_type ]['only_slug'] ) {
		return;
	}
	if ( ! isset( $_POST['hn_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hn_meta_nonce'] ) ), 'hn_meta_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( $defs[ $post->post_type ]['fields'] as $key => $f ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		switch ( $f['type'] ) {
			case 'date':
				$val = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ? $raw : '';
				break;
			case 'url':
				$val = esc_url_raw( $raw );
				break;
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				break;
			case 'focus':
				$val = preg_match( '/^\d{1,3}% \d{1,3}%$/', $raw ) ? $raw : '50% 30%';
				break;
			default:
				$val = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $val );
	}
	// 千秋楽が空なら初日と同じ日にする(「終わっていない公演」の判定に使う)
	if ( 'hn_schedule' === $post->post_type ) {
		$start = get_post_meta( $post_id, '_hn_start', true );
		$end   = get_post_meta( $post_id, '_hn_end', true );
		if ( $start && ( ! $end || $end < $start ) ) {
			update_post_meta( $post_id, '_hn_end', $start );
		}
	}
}, 10, 2 );
