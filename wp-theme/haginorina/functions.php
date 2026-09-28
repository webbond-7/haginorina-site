<?php
/**
 * はぎのりな Official Fan Site テーマ
 *
 * inc/helpers.php     … テンプレートで使う小さな関数
 * inc/post-types.php  … 公演予定・役のカード・写真・お問い合わせ履歴
 * inc/meta.php        … それぞれの入力欄
 * inc/customizer.php  … お問い合わせの送信先など
 * inc/contact.php     … お問い合わせフォームの送信
 * inc/seed.php        … 初期データの投入(ツール → はぎのりな 初期データ)
 * inc/private-mode.php … 公開前モード(ログインしていない人には「準備中」だけを見せる)
 * inc/editable.php   … トップの写真・プロフィール・活動紹介の一覧を管理画面から直す
 */

defined( 'ABSPATH' ) || exit;

define( 'HN_VER', '1.0.0' );

require get_theme_file_path( 'inc/helpers.php' );
require get_theme_file_path( 'inc/post-types.php' );
require get_theme_file_path( 'inc/meta.php' );
require get_theme_file_path( 'inc/customizer.php' );
require get_theme_file_path( 'inc/contact.php' );
require get_theme_file_path( 'inc/seed.php' );
require get_theme_file_path( 'inc/private-mode.php' );
require get_theme_file_path( 'inc/editable.php' );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_image_size( 'hn-large', 1600, 1600, false );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'hn-fonts', 'https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700;900&family=Baloo+2:wght@500;600;700;800&family=Caveat:wght@500;700&display=swap', array(), null );
	wp_enqueue_style( 'hn-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
	wp_enqueue_style( 'hn-style', get_theme_file_uri( 'assets/css/style.css' ), array(), HN_VER );
	wp_enqueue_script( 'hn-main', get_theme_file_uri( 'assets/js/main.js' ), array(), HN_VER, true );
} );

// Google Fonts の事前接続
add_filter( 'wp_resource_hints', function ( $urls, $type ) {
	if ( 'preconnect' === $type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

// 検索結果に出る説明文
add_action( 'wp_head', function () {
	$desc = 'はぎのりな 公式ファンサイト。舞台・映像・モデル活動・写真・お知らせなどを発信します。';
	if ( is_singular( 'post' ) ) {
		$desc = wp_strip_all_tags( get_the_excerpt() );
	} elseif ( is_home() || is_category() || is_date() ) {
		$desc = 'はぎのりな 公式ファンサイト News / Info。公演予定・出演情報・お知らせをお届けします。';
	} elseif ( is_page( 'works' ) ) {
		$desc = 'はぎのりなの活動紹介。舞台・映像・モデルと、ジャンルを横断した表現活動を紹介します。';
	} elseif ( is_page( 'gallery' ) ) {
		$desc = 'はぎのりな 公式ファンサイト Photo / Gallery。舞台・撮影・オフショットなどの写真を掲載します。';
	} elseif ( is_page( 'contact' ) ) {
		$desc = 'はぎのりな 公式ファンサイト。お問い合わせフォーム・SNSリンク・依頼内容について。';
	}
	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( wp_trim_words( $desc, 120, '…' ) ) );
}, 1 );

// タイトルの区切り
add_filter( 'document_title_separator', function () {
	return '｜';
} );

// ニュース一覧は 1ページ 10件
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( $q->is_home() || $q->is_category() || $q->is_date() ) ) {
		$q->set( 'posts_per_page', 10 );
	}
} );

// 404 は検索結果に出さない
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_404() ) {
		$robots['noindex'] = true;
	}
	return $robots;
} );
