<?php
/**
 * 管理画面から直せるようにした箇所
 *
 * - トップの最初の写真(3枚)とプロフィール … 外観 → カスタマイズ → トップページ
 * - 活動紹介の「主な出演作」「撮影の内容」  … 固定ページ「活動紹介」の編集画面(inc/meta.php)
 *
 * どれも空欄なら、テーマに入っている元の中身を出す。
 */

defined( 'ABSPATH' ) || exit;

/** トップの最初の写真の初期値(テーマ内の画像と、顔認識で合わせた位置) */
function hn_hero_defaults() {
	return array(
		1 => array( 'hero-main-01.jpg', '100% 50%' ),
		2 => array( 'hero-main-02.jpg', '34% 50%' ),
		3 => array( 'hero-main-03.jpg', '10% 50%' ),
	);
}

/** プロフィールの初期値 */
function hn_profile_defaults() {
	return array(
		'name'     => 'はぎのりな / Haginorina',
		'birthday' => '1991年10月3日',
		'activity' => '役者 / モデル / 脚本・演出',
		'field'    => '舞台 / 映像・配信 / モデル',
		'status'   => '出演・撮影・コラボ 受付中',
		'text'     => "表現することが大好きです。舞台や映像、撮影──色々な場所で、見てくれる人と少しだけ感情を共有できたら嬉しいです。\nふつうとヘンのあわいで、ちいさな魔法を起こすつもりで活動しています。",
	);
}

/** カスタマイズの値(空なら初期値) */
function hn_mod( $key, $default = '' ) {
	$v = get_theme_mod( $key, '' );
	return ( '' === $v || null === $v ) ? $default : $v;
}

/** 写真の位置の選択肢に、初期値(個別に合わせた値)も加える */
function hn_focus_choices_with( $value ) {
	$c = hn_focus_choices();
	if ( $value && ! isset( $c[ $value ] ) ) {
		$c = array( $value => '最初の位置(顔に合わせた値)' ) + $c;
	}
	return $c;
}

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section(
		'hn_front',
		array(
			'title'       => 'トップページ',
			'priority'    => 31,
			'description' => '空欄のままなら、今の写真と文章が出ます。',
		)
	);

	foreach ( hn_hero_defaults() as $n => $d ) {
		$wp_customize->add_setting( "hn_hero_{$n}", array( 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				"hn_hero_{$n}",
				array(
					'label'     => "最初の写真 {$n}枚目",
					'section'   => 'hn_front',
					'mime_type' => 'image',
				)
			)
		);
		$wp_customize->add_setting( "hn_hero_{$n}_pos", array( 'default' => $d[1], 'sanitize_callback' => 'hn_sanitize_focus' ) );
		$wp_customize->add_control(
			"hn_hero_{$n}_pos",
			array(
				'label'   => "{$n}枚目の顔の位置",
				'section' => 'hn_front',
				'type'    => 'select',
				'choices' => hn_focus_choices_with( $d[1] ),
			)
		);
	}

	$wp_customize->add_setting( 'hn_profile_photo', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'hn_profile_photo',
			array(
				'label'     => 'プロフィールの写真',
				'section'   => 'hn_front',
				'mime_type' => 'image',
			)
		)
	);
	$wp_customize->add_setting( 'hn_profile_photo_pos', array( 'default' => '50% 0%', 'sanitize_callback' => 'hn_sanitize_focus' ) );
	$wp_customize->add_control(
		'hn_profile_photo_pos',
		array(
			'label'   => 'プロフィールの写真の顔の位置',
			'section' => 'hn_front',
			'type'    => 'select',
			'choices' => hn_focus_choices_with( '50% 0%' ),
		)
	);

	$labels = array(
		'name'     => 'プロフィール: Name',
		'birthday' => 'プロフィール: Birthday',
		'activity' => 'プロフィール: Activity',
		'field'    => 'プロフィール: Field',
		'status'   => 'プロフィール: Status',
		'text'     => 'プロフィール: 紹介文(改行はそのまま反映)',
	);
	foreach ( hn_profile_defaults() as $k => $default ) {
		$wp_customize->add_setting(
			"hn_profile_{$k}",
			array(
				'default'           => $default,
				'sanitize_callback' => 'text' === $k ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"hn_profile_{$k}",
			array(
				'label'   => $labels[ $k ],
				'section' => 'hn_front',
				'type'    => 'text' === $k ? 'textarea' : 'text',
			)
		);
	}
} );

function hn_sanitize_focus( $v ) {
	return preg_match( '/^\d{1,3}% \d{1,3}%$/', (string) $v ) ? $v : '50% 30%';
}

/** トップの最初の写真 n枚目 */
function hn_hero_img( $n ) {
	$defaults = hn_hero_defaults();
	$pos      = hn_mod( "hn_hero_{$n}_pos", $defaults[ $n ][1] );
	$id       = (int) get_theme_mod( "hn_hero_{$n}", 0 );
	$extra    = 1 === $n ? array() : array( 'aria-hidden' => 'true' );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image(
			$id,
			'hn-large',
			false,
			array_merge(
				array(
					'alt'     => 'はぎのりな メインビジュアル ' . $n,
					'style'   => 'object-position: ' . esc_attr( $pos ) . ';',
					'loading' => 1 === $n ? 'eager' : 'lazy',
				),
				$extra
			)
		);
	}
	return sprintf(
		'<img src="%s" alt="はぎのりな メインビジュアル %d"%s style="object-position: %s;" />',
		hn_asset( 'images/' . $defaults[ $n ][0] ),
		$n,
		$extra ? ' aria-hidden="true"' : '',
		esc_attr( $pos )
	);
}

/** プロフィールの写真 */
function hn_profile_img() {
	$pos   = hn_mod( 'hn_profile_photo_pos', '50% 0%' );
	$style = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position: ' . esc_attr( $pos ) . ';';
	$id    = (int) get_theme_mod( 'hn_profile_photo', 0 );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image( $id, 'hn-large', false, array( 'alt' => 'はぎのりな プロフィールフォト', 'style' => $style ) );
	}
	return '<img src="' . hn_asset( 'images/profile.jpg' ) . '" alt="はぎのりな プロフィールフォト" style="' . $style . '" />';
}

/** プロフィールの項目 */
function hn_profile( $key ) {
	$d = hn_profile_defaults();
	return hn_mod( "hn_profile_{$key}", $d[ $key ] );
}

/** 活動紹介の一覧(固定ページ「活動紹介」の入力欄。1行1項目。空なら初期値) */
function hn_work_list( $key, $defaults ) {
	$page  = get_page_by_path( 'works' );
	$text  = $page ? (string) get_post_meta( $page->ID, $key, true ) : '';
	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $text ) ), 'strlen' );
	foreach ( $lines ? $lines : $defaults as $line ) {
		echo '          <li>' . esc_html( $line ) . "</li>\n";
	}
}
