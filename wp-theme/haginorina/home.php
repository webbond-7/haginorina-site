<?php
/**
 * ニュース一覧(設定 → 表示設定 の「投稿ページ」)
 */
get_header();
?>
  <style>
    .schedule-grid { display:grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 40px; }
    .schedule-card {
      position: relative; padding: 32px; border-radius: var(--radius-lg);
      background:#fff; box-shadow: var(--shadow-soft);
      transition: transform .3s ease, box-shadow .3s ease;
      overflow: hidden;
    }
    .schedule-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(230,57,70,.12); }
    .schedule-card .date {
      display:flex; align-items:baseline; gap:8px;
      font-family: var(--font-en); color: var(--color-red); margin-bottom: 18px;
    }
    .schedule-card .date .d { font-size: 44px; font-weight:700; line-height:1; }
    .schedule-card .date .m { font-size: 14px; letter-spacing:0.12em; }
    .schedule-card h3 { font-size: 20px; margin-bottom: 10px; }
    .schedule-card .meta { font-size: 13px; color: var(--color-text-sub); line-height: 2; }
    .schedule-card::after {
      content:""; position: absolute; top:-30px; right:-30px;
      width:100px; height:100px; border-radius:50%;
      background: var(--color-yellow-soft); z-index:0;
      transition: transform .4s ease;
    }
    .schedule-card:hover::after { transform: scale(1.6); }
    .schedule-card > * { position: relative; z-index: 1; }
    @media (max-width: 1023px) { .schedule-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 767px) { .schedule-grid { grid-template-columns: 1fr; } }
    a.schedule-card { display: block; color: inherit; }
  </style>


<section class="page-hero">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:80px;left:-60px;width:180px;height:180px;background:var(--color-yellow);opacity:.5;"></span>
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;top:140px;right:10%;width:60px;height:60px;background:var(--color-red-soft);"></span>
  <span class="deco deco-ring float-medium" aria-hidden="true" style="position:absolute;bottom:40px;right:30%;width:50px;height:50px;border-color:var(--color-red);"></span>

  <div class="page-hero-inner">
    <div class="page-hero-text">
      <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp; NEWS / INFO</div>
      <h1>
        <span class="jp">NEWS / INFO — お知らせ</span>
        News.
      </h1>
      <p>公演予定・出演情報のお知らせ。<br>最新の活動をこちらからご確認ください。</p>
    </div>
    <div class="page-hero-photo">
      <img style="object-position: 0% 50%;" src="<?php echo hn_asset( 'images/g08.jpg' ); ?>" alt="はぎのりな ポートレート" />
    </div>
  </div>
</section>

<!-- UPCOMING SCHEDULE -->
<section class="section section-bg-soft">
  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">UPCOMING — 公演予定</span>
        Upcoming.
      </h2>
    </div>

<?php get_template_part( 'template-parts/schedule-cards' ); ?>
  </div>
</section>

<!-- ALL NEWS -->
<section class="section section-bg-white">
  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">ALL NEWS — すべてのお知らせ</span>
        All news.
      </h2>
    </div>

    <!-- Category tabs -->
    <?php hn_category_tabs( 'all' ); ?>
    <?php hn_month_select(); ?>

    <div class="news-list reveal">
<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		hn_news_item();
	}
} else {
	echo '<p style="color:var(--color-text-sub);">お知らせはまだありません。</p>';
}
?>
    </div>
    <?php hn_pagination(); ?>
  </div>
</section>

<?php get_footer(); ?>
