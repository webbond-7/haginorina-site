<?php
/**
 * 外観 → カスタマイズ → はぎのりな 設定
 */

defined( 'ABSPATH' ) || exit;

define( 'HN_CONTACT_TO_DEFAULT', 'sobo74sobo@gmail.com' );

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section(
		'hn_settings',
		array(
			'title'    => 'はぎのりな 設定',
			'priority' => 30,
		)
	);
	$wp_customize->add_setting(
		'hn_contact_to',
		array(
			'default'           => HN_CONTACT_TO_DEFAULT,
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'hn_contact_to',
		array(
			'label'       => 'お問い合わせの送信先メールアドレス',
			'description' => 'フォームから届いた内容は、このアドレスに送られます。管理画面の「お問い合わせ履歴」にも控えが残ります。',
			'section'     => 'hn_settings',
			'type'        => 'email',
		)
	);
} );

function hn_contact_to() {
	$to = get_theme_mod( 'hn_contact_to', HN_CONTACT_TO_DEFAULT );
	return is_email( $to ) ? $to : get_option( 'admin_email' );
}
