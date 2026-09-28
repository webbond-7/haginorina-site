<?php
/**
 * 投稿の種類
 *
 * ニュース        … WordPress 標準の「投稿」(カテゴリ: 舞台 / 配信・映像 / 演出・脚本 / お知らせ)
 * 公演予定        … hn_schedule   トップの「次の舞台」とニュースの「公演予定」に自動で出る
 * 役のカード      … hn_role       活動紹介の「これまでの役」
 * 写真            … hn_photo      ギャラリー(分類: hn_photo_cat)
 * お問い合わせ履歴 … hn_inquiry    フォームから届いた内容の控え(メールが届かなかったときの保険)
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	$private = array(
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_rest'       => false,
		'exclude_from_search' => true,
		'has_archive'        => false,
		'rewrite'            => false,
	);

	register_post_type(
		'hn_schedule',
		array_merge(
			$private,
			array(
				'labels'        => array(
					'name'          => '公演予定',
					'singular_name' => '公演予定',
					'add_new'       => '公演予定を追加',
					'add_new_item'  => '公演予定を追加',
					'edit_item'     => '公演予定を編集',
					'all_items'     => '公演予定の一覧',
				),
				'menu_icon'     => 'dashicons-calendar-alt',
				'menu_position' => 6,
				'supports'      => array( 'title' ),
			)
		)
	);

	register_post_type(
		'hn_role',
		array_merge(
			$private,
			array(
				'labels'        => array(
					'name'          => '役のカード',
					'singular_name' => '役のカード',
					'add_new'       => 'カードを追加',
					'add_new_item'  => '役のカードを追加',
					'edit_item'     => '役のカードを編集',
					'all_items'     => '役のカードの一覧',
				),
				'menu_icon'     => 'dashicons-id-alt',
				'menu_position' => 7,
				'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'hn_photo',
		array_merge(
			$private,
			array(
				'labels'        => array(
					'name'          => '写真',
					'singular_name' => '写真',
					'add_new'       => '写真を追加',
					'add_new_item'  => '写真を追加',
					'edit_item'     => '写真を編集',
					'all_items'     => '写真の一覧',
					'featured_image'     => '写真',
					'set_featured_image' => '写真を選ぶ',
				),
				'menu_icon'     => 'dashicons-format-gallery',
				'menu_position' => 8,
				'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_taxonomy(
		'hn_photo_cat',
		'hn_photo',
		array(
			'labels'            => array(
				'name'          => '写真の分類',
				'singular_name' => '写真の分類',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);

	register_post_type(
		'hn_inquiry',
		array_merge(
			$private,
			array(
				'labels'       => array(
					'name'          => 'お問い合わせ履歴',
					'singular_name' => 'お問い合わせ',
					'all_items'     => 'お問い合わせ履歴',
					'edit_item'     => 'お問い合わせの内容',
				),
				'menu_icon'    => 'dashicons-email-alt',
				'menu_position' => 26,
				'supports'     => array( 'title', 'editor' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap' => true,
			)
		)
	);
} );

// 管理画面の一覧に日付や分類を出す
add_filter( 'manage_hn_schedule_posts_columns', function ( $cols ) {
	return array(
		'cb'       => $cols['cb'],
		'title'    => '公演名',
		'hn_when'  => '日程',
		'hn_venue' => '会場',
		'hn_part'  => '関わり方',
	);
} );
add_action( 'manage_hn_schedule_posts_custom_column', function ( $col, $id ) {
	$s = hn_schedule( $id );
	if ( 'hn_when' === $col ) {
		echo esc_html( hn_range_text( $s['start'], $s['end'] ) );
	} elseif ( 'hn_venue' === $col ) {
		echo esc_html( $s['venue'] );
	} elseif ( 'hn_part' === $col ) {
		echo esc_html( $s['part'] );
	}
}, 10, 2 );

foreach ( array( 'hn_role', 'hn_photo' ) as $pt ) {
	add_filter( "manage_{$pt}_posts_columns", function ( $cols ) {
		$new = array( 'cb' => $cols['cb'], 'hn_thumb' => '写真' );
		unset( $cols['cb'] );
		return array_merge( $new, $cols );
	} );
	add_action( "manage_{$pt}_posts_custom_column", function ( $col, $id ) {
		if ( 'hn_thumb' === $col ) {
			echo get_the_post_thumbnail( $id, array( 60, 60 ), array( 'style' => 'width:60px;height:60px;object-fit:cover;border-radius:6px;' ) );
		}
	}, 10, 2 );
}

// 役のカード・写真は「並び順」の小さい順に並べる
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() && $q->is_main_query() && in_array( $q->get( 'post_type' ), array( 'hn_role', 'hn_photo' ), true ) && ! $q->get( 'orderby' ) ) {
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}
} );
