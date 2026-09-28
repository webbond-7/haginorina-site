<?php
/**
 * 初期データの投入(ツール → はぎのりな 初期データ)
 *
 * 静的な試作(haginorina-site/*.html)と同じ中身を WordPress に入れる。
 * 1回だけ動く(2回押しても同じものは増えない)。管理者だけが実行できる。
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_management_page( 'はぎのりな 初期データ', 'はぎのりな 初期データ', 'manage_options', 'hn-seed', 'hn_seed_page' );
} );

function hn_seed_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$log = array();
	if ( isset( $_POST['hn_seed'] ) && check_admin_referer( 'hn_seed' ) ) {
		$log = hn_seed_run( ! empty( $_POST['hn_trash_samples'] ) );
	}
	echo '<div class="wrap"><h1>はぎのりな 初期データ</h1>';
	echo '<p>ページ(ホーム・ニュース・活動紹介・ギャラリー・お問い合わせ)、ニュース、公演予定、役のカード、写真をまとめて入れます。すでにあるものは作り直しません。</p>';
	if ( $log ) {
		echo '<div class="notice notice-success"><p>' . implode( '<br>', array_map( 'esc_html', $log ) ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'hn_seed' );
	echo '<p><label><input type="checkbox" name="hn_trash_samples" value="1" checked> WordPress に最初から入っている見本(「Hello world!」の投稿と「サンプルページ」)をゴミ箱へ移す</label></p>';
	echo '<p class="description">パーマリンク(URLの形)は「投稿名」にします。</p>';
	submit_button( '初期データを入れる', 'primary', 'hn_seed' );
	echo '</form></div>';
}

/** テーマ内の画像をメディアライブラリに入れる(同じファイル名があれば使い回す) */
function hn_seed_media( $file, $parent = 0, $alt = 'はぎのりな' ) {
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_hn_seed_file',
			'meta_value'  => $file,
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$src = get_theme_file_path( 'assets/images/' . $file );
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$tmp = wp_tempnam( $file );
	copy( $src, $tmp );
	$id = media_handle_sideload( array( 'name' => 'haginorina-' . $file, 'tmp_name' => $tmp ), $parent );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	update_post_meta( $id, '_hn_seed_file', $file );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	return (int) $id;
}

/** 同じタイトルが無ければ作る */
function hn_seed_post( $args, $meta = array() ) {
	$exists = get_posts(
		array(
			'post_type'   => $args['post_type'],
			'title'       => $args['post_title'],
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	if ( $exists ) {
		return array( (int) $exists[0], false );
	}
	$id = wp_insert_post( array_merge( array( 'post_status' => 'publish' ), $args ) );
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	return array( $id, true );
}

function hn_seed_run( $trash_samples = false ) {
	$log = array();

	if ( $trash_samples ) {
		foreach ( array( array( 'post', 'hello-world' ), array( 'page', 'sample-page' ) ) as $sample ) {
			$p = get_page_by_path( $sample[1], OBJECT, $sample[0] );
			if ( $p && 'trash' !== $p->post_status ) {
				wp_trash_post( $p->ID );
				$log[] = '見本をゴミ箱へ移しました: ' . $p->post_title;
			}
		}
	}

	// ---- ページ ----
	$pages = array(
		'home'    => 'ホーム',
		'news'    => 'News / Info',
		'works'   => '活動紹介',
		'gallery' => 'Photo / Gallery',
		'contact' => 'Contact & SNS',
	);
	$ids = array();
	foreach ( $pages as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$ids[ $slug ] = $page->ID;
			continue;
		}
		$ids[ $slug ] = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
			)
		);
		$log[] = "ページを作りました: {$title}";
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', $ids['news'] );
	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' ); // 同じ処理の中でも新しい形で転送ルールを作る
		$log[] = 'パーマリンクを「投稿名」にしました';
	}
	flush_rewrite_rules();

	// ---- ニュースのカテゴリ ----
	$cats = array(
		'stage'  => '舞台',
		'stream' => '配信・映像',
		'direct' => '演出・脚本',
		'info'   => 'お知らせ',
	);
	$cat_ids = array();
	foreach ( $cats as $slug => $name ) {
		$t = get_term_by( 'slug', $slug, 'category' );
		if ( ! $t ) {
			$r = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
			$cat_ids[ $slug ] = is_wp_error( $r ) ? 0 : $r['term_id'];
		} else {
			$cat_ids[ $slug ] = $t->term_id;
		}
	}

	// ---- ニュース ----
	$tlpt = 'https://oracleknights.co.jp/jinrou-tlpt/information.php';
	$news = array(
		array( 'qt-2026', '2026-05-27', 'stage', 'イルカ団!!「Q.T!!!!」に琴音役で出演します(5/27〜31 ウッディシアター中目黒)', "イルカ団!!「Q.T!!!!」に、Bチームの琴音役で出演します。\n\n日程：2026年5月27日(水)〜31日(日)\n会場：ウッディシアター中目黒", 'role-kotone-stage.jpg' ),
		array( 'chakin-chakin-10th', '2026-07-25', 'stage', '劇団ちゃきんちゃきん10周年記念公演に日替わりゲストで出演します(10/25 昼の回)', "東京インプロ劇団ちゃきんちゃきんの10周年記念公演に、日替わりゲストとして出演します。\n\n出演日：2026年10月25日(日) 昼の回(こども歓迎DAY)", '' ),
		array( 'tlpt-58-village-direction', '2026-07-31', 'direct', '人狼TLPT #58「ヴィレッヂ」の演出を担当します(2027年2月 シアター1010ミニシアター)', "人狼 ザ・ライブプレイングシアター #58「ヴィレッヂ」の演出を担当します。\n\n上演：2027年2月\n会場：シアター1010ミニシアター\n主催：オラクルナイツ\n\n詳細は<a href=\"{$tlpt}\">人狼TLPT公式サイト</a>をご覧ください。", '' ),
		array( 'gnosia-tlpt-comet', '2026-08-21', 'stage', '「グノーシア ザ・ライブプレイングシアター」にコメット役で出演します(8/21〜9/6 飛行船シアター)', "「グノーシア ザ・ライブプレイングシアター」に、コメット役で出演します。\n\n日程：2026年8月21日(金)〜9月6日(日)\n会場：飛行船シアター", 'x02.jpg' ),
		array( 'seventh-eden-139', '2026-08-26', 'stream', '人狼TLPT公式番組「セブンスエデン」第139回にゲスト出演しました', "人狼TLPTの公式番組「セブンスエデン」第139回に、ゲストとして出演しました。", '' ),
		array( 'tlpt-14th-festival-stage', '2026-09-04', 'stage', '人狼TLPT 14周年記念「Festival Stage」全ステージに出演します(10/13・14 新宿村LIVE)', "「人狼 ザ・ライブプレイングシアター」の14周年を記念したステージに出演します。10月13日・14日の全3ステージ、すべての回に出演します。\n\n<h2>公演概要</h2>\n<ul>\n<li><strong>タイトル：</strong>Thanks 14th TLPT Anniversary!!! Festival Stage</li>\n<li><strong>会場：</strong>新宿村LIVE</li>\n<li><strong>日程：</strong>2026年10月13日(火)18:00開演／10月14日(水)12:00開演・17:00開演</li>\n<li><strong>主催：</strong>オラクルナイツ</li>\n</ul>\n\n<h2>はぎのりなより</h2>\n<blockquote>全ての回に出演させていただきます！<br>おまつりステージだってさｧ～～ｯ</blockquote>\n\nチケット・詳細は<a href=\"{$tlpt}\">人狼TLPT公式サイト</a>をご覧ください。", 'x06.jpg' ),
		array( 'dopeadope-step13-talk', '2026-09-13', 'stage', 'dopeAdope step.13「ドープアウト」アフターミニトークに出演します(9/20 上野ストアハウス)', "dopeAdope step.13「ドープアウト」のアフターミニトークに出演します。\n\n日時：2026年9月20日(日) 13:00の回\n会場：上野ストアハウス", '' ),
		array( 'ultimate-jinro-sriaro', '2026-09-22', 'stream', '「アルティメット人狼」スリアロコラボ回に出演しました', "「アルティメット人狼」のスリアロコラボ回に出演しました。アルティメット人狼チャンネルの3戦目に出ています。", '' ),
	);
	foreach ( $news as $n ) {
		list( $id, $new ) = hn_seed_post(
			array(
				'post_type'     => 'post',
				'post_name'     => $n[0],
				'post_title'    => $n[3],
				'post_content'  => $n[4],
				'post_date'     => $n[1] . ' 12:00:00',
				'post_category' => array( $cat_ids[ $n[2] ] ),
			)
		);
		if ( $new ) {
			if ( $n[5] ) {
				set_post_thumbnail( $id, hn_seed_media( $n[5], $id ) );
				// 記事上部の写真(横長の枠)で顔が切れない位置。顔認識で測った値から計算
				$focus = array( 'x06.jpg' => '50% 14%', 'x02.jpg' => '50% 29%', 'role-kotone-stage.jpg' => '60% 38%' );
				if ( isset( $focus[ $n[5] ] ) ) {
					update_post_meta( $id, '_hn_focus', $focus[ $n[5] ] );
				}
			}
			$log[] = "ニュース: {$n[3]}";
		}
	}
	$festival = get_posts( array( 'post_type' => 'post', 'title' => $news[5][3], 'numberposts' => 1, 'fields' => 'ids' ) );
	$festival_url = $festival ? get_permalink( $festival[0] ) : $tlpt;

	// ---- 公演予定 ----
	$schedules = array(
		array( '人狼TLPT 14周年記念「Festival Stage」', '2026-10-13', '2026-10-14', '新宿村LIVE', '全ステージ出演', $festival_url ),
		array( '劇団ちゃきんちゃきん 10周年記念公演', '2026-10-25', '2026-10-25', '昼の回(こども歓迎DAY)', 'ゲスト出演', hn_url( 'news' ) ),
		array( '人狼TLPT #58「ヴィレッヂ」', '2027-02-01', '2027-02-28', 'シアター1010ミニシアター', '演出', $tlpt, '2027年2月' ),
	);
	foreach ( $schedules as $s ) {
		list( , $new ) = hn_seed_post(
			array( 'post_type' => 'hn_schedule', 'post_title' => $s[0] ),
			array( '_hn_start' => $s[1], '_hn_end' => $s[2], '_hn_venue' => $s[3], '_hn_part' => $s[4], '_hn_url' => $s[5], '_hn_when' => isset( $s[6] ) ? $s[6] : '' )
		);
		if ( $new ) {
			$log[] = "公演予定: {$s[0]}";
		}
	}

	// ---- 役のカード ----
	$roles = array(
		array( 'コメット', 'x02.jpg', '2026', 'ROLE', '「グノーシア ザ・ライブプレイングシアター」', '期間', '2026.8.21 – 9.6', '劇場', '飛行船シアター', '50% 30%' ),
		array( '緑川夫人', 'role-midorikawa.jpg', '2026', 'ROLE', 'イルカ団!!「アルセーヌ・ルパン!!!!」', '期間', '2026.2.18 – 3.1', '劇場', 'ウッディシアター中目黒', '50% 30%' ),
		array( '琴音', 'role-kotone-stage.jpg', '2026', 'ROLE', 'イルカ団!!「Q.T!!!!」', '期間', '2026.5.27 – 5.31', '劇場', 'ウッディシアター中目黒', '60% 30%' ),
		array( '素顔', 'mirror-selfie.jpg', 'はぎのりな', 'NO ROLE', 'はぎのりな', '仕事', 'フリーの役者 / たまにモデル', 'ほかに', '脚本・演出も手がける', '55% 30%' ),
	);
	foreach ( $roles as $i => $r ) {
		list( $id, $new ) = hn_seed_post(
			array( 'post_type' => 'hn_role', 'post_title' => $r[0], 'menu_order' => $i ),
			array( '_hn_sub' => $r[2], '_hn_kind' => $r[3], '_hn_work' => $r[4], '_hn_l1' => $r[5], '_hn_period' => $r[6], '_hn_l2' => $r[7], '_hn_venue' => $r[8], '_hn_focus' => $r[9] )
		);
		if ( $new ) {
			set_post_thumbnail( $id, hn_seed_media( $r[1], $id, $r[0] . '役のはぎのりな' ) );
			$log[] = "役のカード: {$r[0]}";
		}
	}

	// ---- 写真(ギャラリー) ----
	$photo_cats = array( 'stage' => 'STAGE', 'film' => 'FILM', 'model' => 'MODEL', 'offshot' => 'OFFSHOT' );
	foreach ( $photo_cats as $slug => $name ) {
		if ( ! get_term_by( 'slug', $slug, 'hn_photo_cat' ) ) {
			wp_insert_term( $name, 'hn_photo_cat', array( 'slug' => $slug ) );
		}
	}
	// 顔の位置は、Macの顔認識で測った値から計算した(2026-09-25)
	$photos = array(
		array( 'g01.jpg', 'model', '50% 77%' ), array( 'g04.jpg', 'model', '50% 26%' ), array( 'g05.jpg', 'model', '50% 24%' ),
		array( 'g03.jpg', 'model', '50% 61%' ), array( 'g06.jpg', 'model', '50% 100%' ), array( 'g07.jpg', 'model', '50% 22%' ),
		array( 'g02.jpg', 'model', '50% 53%' ), array( 'g08.jpg', 'model', '50% 2%' ), array( 'g09.jpg', 'model', '50% 20%' ),
		array( 'x02.jpg', 'offshot', '50% 32%' ), array( 'x04.jpg', 'offshot', '50% 9%' ), array( 'x01.jpg', 'offshot', '50% 26%' ),
		array( 'x03.jpg', 'offshot', '50% 17%' ), array( 'x05.jpg', 'offshot', '50% 24%' ), array( 'x06.jpg', 'offshot', '50% 20%' ),
		array( 'role-midorikawa.jpg', 'stage', '50% 25%' ), array( 'role-kotone-stage.jpg', 'stage', '60% 30%' ),
	);
	foreach ( $photos as $i => $ph ) {
		$title = '写真 ' . str_replace( '.jpg', '', $ph[0] );
		list( $id, $new ) = hn_seed_post(
			array( 'post_type' => 'hn_photo', 'post_title' => $title, 'menu_order' => $i ),
			array( '_hn_focus' => $ph[2] )
		);
		if ( $new ) {
			set_post_thumbnail( $id, hn_seed_media( $ph[0], $id ) );
			wp_set_object_terms( $id, $ph[1], 'hn_photo_cat' );
			$log[] = "写真: {$ph[0]}";
		}
	}

	if ( ! $log ) {
		$log[] = '入れるものはありませんでした(すべて入っています)';
	}
	return $log;
}
