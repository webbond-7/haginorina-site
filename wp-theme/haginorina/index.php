<?php
/**
 * どのテンプレートにも当てはまらないときの表示
 */
get_header();
?>
<section class="section section-bg-white" style="padding-top:160px;">
  <div class="section-inner">
    <div class="news-list">
<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		hn_news_item();
	}
}
?>
    </div>
    <?php hn_pagination(); ?>
  </div>
</section>
<?php get_footer(); ?>
