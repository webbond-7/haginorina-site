<?php
/**
 * カテゴリ別のニュース一覧
 */
get_header();
$hn_term  = get_queried_object();
$hn_label = hn_category_label( $hn_term->slug );
?>
<!-- PAGE HERO -->
<section class="page-hero">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:60px;left:-40px;width:180px;height:180px;background:var(--color-yellow-soft);"></span>
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;bottom:80px;right:8%;width:60px;height:60px;background:var(--color-red);opacity:.18;"></span>
  <span class="deco deco-ring float-medium" aria-hidden="true" style="position:absolute;top:140px;right:20%;width:50px;height:50px;border-color:var(--color-red);"></span>

  <div class="page-hero-inner">
    <div class="page-hero-text">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp;
        <a href="<?php echo esc_url( hn_url( 'news' ) ); ?>">NEWS</a> &nbsp;/&nbsp;
        カテゴリ：<?php single_cat_title(); ?>
      </div>
      <h1>
        <span class="jp">CATEGORY — カテゴリ</span>
        <?php echo esc_html( $hn_label[0] ); ?>
      </h1>
      <p><?php echo esc_html( $hn_label[1] ); ?><br>該当する記事は <strong><?php echo (int) $hn_term->count; ?>件</strong> です。</p>
    </div>
    <div class="page-hero-photo">
      <img src="<?php echo hn_asset( 'images/g03.jpg' ); ?>" alt="" style="object-position: 100% 50%;" />
    </div>
  </div>
</section>

<!-- LIST -->
<section class="section section-bg-soft">
  <div class="section-inner">

    <!-- Category tabs -->
    <?php hn_category_tabs( $hn_term->term_id ); ?>
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
