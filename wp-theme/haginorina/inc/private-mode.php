<?php
/**
 * 公開前モード
 *
 * オンのあいだ、ログインしていない人には「準備中」だけを見せる。
 * - ステータスは 503(一時的に見られない)。検索エンジンは中身を登録しない
 * - REST API とフィードも、ログインしていない人には閉じる
 * 切り替え: 外観 → カスタマイズ → はぎのりな 設定 → 「公開前モード」
 * 公開するときは、設定 → 表示設定 の「検索エンジンでの表示」も外すこと
 */

defined( 'ABSPATH' ) || exit;

function hn_is_private_mode() {
	return (bool) get_theme_mod( 'hn_private_mode', true );
}

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_setting(
		'hn_private_mode',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'hn_private_mode',
		array(
			'label'       => '公開前モード(ログインしていない人には「準備中」だけを見せる)',
			'description' => '公開するときはチェックを外してください。あわせて「設定 → 表示設定 → 検索エンジンでの表示」のチェックも外します。',
			'section'     => 'hn_settings',
			'type'        => 'checkbox',
		)
	);
}, 20 );

add_action( 'template_redirect', function () {
	// robots.txt は通す(検索に出さないのは、各ページの noindex で行う)
	if ( ! hn_is_private_mode() || is_user_logged_in() || is_robots() ) {
		return;
	}
	status_header( 503 );
	header( 'Retry-After: 86400' );
	header( 'X-Robots-Tag: noindex, nofollow' );
	nocache_headers();
	?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="robots" content="noindex, nofollow" />
<title>準備中｜Haginorina</title>
<style>
  body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #F7F6F2; color: #222; font-family: "Zen Maru Gothic", system-ui, sans-serif; text-align: center; }
  h1 { font-size: 40px; color: #E63946; margin: 0 0 12px; letter-spacing: .02em; }
  p { margin: 0; color: #666; line-height: 2; }
</style>
</head>
<body>
  <main>
    <h1>Haginorina</h1>
    <p>公式サイトは、ただいま準備中です。</p>
  </main>
</body>
</html>
	<?php
	exit;
}, 0 );

// ログインしていない人には REST API を閉じる
add_filter( 'rest_authentication_errors', function ( $result ) {
	if ( hn_is_private_mode() && ! is_user_logged_in() && ! is_wp_error( $result ) ) {
		return new WP_Error( 'hn_private', '準備中です。', array( 'status' => 401 ) );
	}
	return $result;
} );
