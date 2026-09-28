<?php
/**
 * テンプレートで使う小さな関数
 */

defined( 'ABSPATH' ) || exit;

/** テーマの assets/ 以下のファイルのURL(エスケープ済み) */
function hn_asset( $path ) {
	return esc_url( get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) ) );
}

/** 各ページのURL。固定ページが無いときは想定のURLを返す */
function hn_url( $key ) {
	if ( 'news' === $key ) {
		$id = (int) get_option( 'page_for_posts' );
		return $id ? get_permalink( $id ) : home_url( '/news/' );
	}
	$page = get_page_by_path( $key );
	return $page ? get_permalink( $page ) : home_url( '/' . $key . '/' );
}

/** ナビの現在地 */
function hn_nav_active( $key ) {
	$on = false;
	switch ( $key ) {
		case 'works':
			$on = is_page( 'works' );
			break;
		case 'gallery':
			$on = is_page( 'gallery' );
			break;
		case 'news':
			$on = is_home() || is_category() || is_date() || is_singular( 'post' );
			break;
		case 'contact':
			$on = is_page( 'contact' );
			break;
	}
	return $on ? ' is-active' : '';
}

/** ニュースのカテゴリ → ラベルの色 */
function hn_tag_class( $slug ) {
	$map = array(
		'stage'  => 'tag-red',
		'stream' => 'tag-yel',
		'info'   => 'tag-yel',
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/** ニュースの1行(一覧・トップで共通) */
function hn_news_item( $post = null ) {
	$post = get_post( $post );
	$cats = get_the_category( $post->ID );
	$cat  = $cats ? $cats[0] : null;
	?>
	<a class="news-item" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
		<span class="news-date"><?php echo esc_html( get_the_date( 'Y.m.d', $post ) ); ?></span>
		<?php if ( $cat ) : ?>
			<span class="news-tag <?php echo esc_attr( hn_tag_class( $cat->slug ) ); ?>"><?php echo esc_html( $cat->name ); ?></span>
		<?php else : ?>
			<span class="news-tag">お知らせ</span>
		<?php endif; ?>
		<span class="news-title"><?php echo esc_html( get_the_title( $post ) ); ?></span>
		<span class="news-arrow">→</span>
	</a>
	<?php
}

/**
 * これからの公演予定(終わっていないもの)を近い順に
 *
 * @return WP_Post[]
 */
function hn_upcoming_schedules( $limit = 3 ) {
	return get_posts(
		array(
			'post_type'      => 'hn_schedule',
			'posts_per_page' => $limit,
			'meta_key'       => '_hn_start',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_hn_end',
					'value'   => wp_date( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
		)
	);
}

/** 公演予定の値 */
function hn_schedule( $post ) {
	$id = is_object( $post ) ? $post->ID : (int) $post;
	return array(
		'start' => get_post_meta( $id, '_hn_start', true ),
		'end'   => get_post_meta( $id, '_hn_end', true ),
		'venue' => get_post_meta( $id, '_hn_venue', true ),
		'part'  => get_post_meta( $id, '_hn_part', true ),
		'url'   => get_post_meta( $id, '_hn_url', true ),
		'when'  => get_post_meta( $id, '_hn_when', true ),
	);
}

/** 2026-10-13 → DateTimeImmutable(サイトのタイムゾーン) */
function hn_date( $ymd ) {
	if ( ! $ymd ) {
		return null;
	}
	$d = DateTimeImmutable::createFromFormat( '!Y-m-d', $ymd, wp_timezone() );
	return $d ? $d : null;
}

/** 帯用の日付「10.13<small>TUE</small> – 10.14<small>WED</small>」 */
function hn_band_date( $start, $end ) {
	$s = hn_date( $start );
	$e = hn_date( $end );
	if ( ! $s ) {
		return '';
	}
	$one = function ( $d ) {
		return esc_html( $d->format( 'n.j' ) ) . '<small>' . esc_html( strtoupper( $d->format( 'D' ) ) ) . '</small>';
	};
	$out = $one( $s );
	if ( $e && $e > $s ) {
		$out .= ' – ' . $one( $e );
	}
	return $out;
}

/** 一覧用の日付「2026.10.13 – 10.14」 */
function hn_range_text( $start, $end ) {
	$s = hn_date( $start );
	$e = hn_date( $end );
	if ( ! $s ) {
		return '';
	}
	$out = $s->format( 'Y.n.j' );
	if ( $e && $e > $s ) {
		$out .= ' – ' . ( $e->format( 'Y' ) === $s->format( 'Y' ) ? $e->format( 'n.j' ) : $e->format( 'Y.n.j' ) );
	}
	return $out;
}

/** 写真の表示位置(object-position) */
function hn_focus( $post_id ) {
	$v = get_post_meta( $post_id, '_hn_focus', true );
	return $v ? $v : '50% 30%';
}

/** 画像タグ(object-fit: cover で枠いっぱい。位置は写真ごとの設定) */
function hn_cover_img( $post_id, $size = 'hn-large', $extra_style = '' ) {
	$thumb = get_post_thumbnail_id( $post_id );
	if ( ! $thumb ) {
		return '';
	}
	$alt = get_post_meta( $thumb, '_wp_attachment_image_alt', true );
	return wp_get_attachment_image(
		$thumb,
		$size,
		false,
		array(
			'alt'   => $alt ? $alt : get_the_title( $post_id ),
			'style' => 'object-position: ' . esc_attr( hn_focus( $post_id ) ) . ';' . $extra_style,
		)
	);
}

/** 公演予定のリンク先(未設定ならニュース一覧) */
function hn_schedule_link( $s ) {
	return $s['url'] ? $s['url'] : hn_url( 'news' );
}

/** ニュースのカテゴリタブ($active: 'all' かカテゴリのID) */
function hn_category_tabs( $active = 'all' ) {
	$total = (int) wp_count_posts( 'post' )->publish;
	echo '<nav class="category-tabs reveal" aria-label="カテゴリ">' . "\n";
	printf( '      <a href="%s"%s>ALL <span class="count">%d</span></a>' . "\n", esc_url( hn_url( 'news' ) ), 'all' === $active ? ' class="is-active" aria-current="page"' : '', $total );
	$cats = get_categories( array( 'hide_empty' => false, 'orderby' => 'term_id', 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
	foreach ( $cats as $c ) {
		printf( '      <a href="%s"%s>%s <span class="count">%d</span></a>' . "\n", esc_url( get_category_link( $c ) ), (int) $active === $c->term_id ? ' class="is-active" aria-current="page"' : '', esc_html( $c->name ), (int) $c->count );
	}
	echo "    </nav>\n";
}

/** ページ送り(見た目は .pagination) */
function hn_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'array',
			'prev_text' => '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i><span class="screen-reader-text">前へ</span>',
			'next_text' => '<i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span class="screen-reader-text">次へ</span>',
		)
	);
	if ( ! $links ) {
		return;
	}
	echo '<nav class="pagination reveal" aria-label="ページネーション">';
	foreach ( $links as $l ) {
		$l = str_replace( 'page-numbers current', 'page-numbers is-current', $l );
		$l = str_replace( 'page-numbers dots', 'page-numbers gap', $l );
		echo $l; // paginate_links の出力はエスケープ済み
	}
	echo '</nav>';
}

/** カテゴリの英字見出しと説明 */
function hn_category_label( $slug ) {
	$map = array(
		'stage'  => array( 'Stage.', '舞台・朗読・客演など、ステージに関するお知らせ。' ),
		'stream' => array( 'Streaming.', '配信番組・映像作品への出演のお知らせ。' ),
		'direct' => array( 'Direction.', '演出・脚本など、つくる側としてのお知らせ。' ),
		'info'   => array( 'Info.', 'サイトや活動についてのお知らせ。' ),
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : array( 'News.', '' );
}

/** 月別の選択欄(選ぶとその月の一覧へ移る) */
function hn_month_select() {
	$opts = wp_get_archives(
		array(
			'type'            => 'monthly',
			'format'          => 'option',
			'show_post_count' => true,
			'echo'            => false,
		)
	);
	if ( ! $opts ) {
		return;
	}
	if ( is_month() ) {
		$cur  = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
		$opts = str_replace( "value='" . esc_url( $cur ) . "'", "value='" . esc_url( $cur ) . "' selected", $opts );
	}
	?>
    <div class="month-select reveal">
      <label for="hn-month">月別</label>
      <select id="hn-month" onchange="if (this.value) { location.href = this.value; }">
        <option value="<?php echo esc_url( hn_url( 'news' ) ); ?>">すべての月</option>
        <?php echo $opts; // wp_get_archives の出力はエスケープ済み ?>
      </select>
    </div>
	<?php
}
