<?php
/**
 * ふつうの固定ページ(プライバシーポリシーなど)
 */
get_header();
the_post();
?>
<section class="page-hero">
  <div class="page-hero-inner" style="grid-template-columns:1fr;">
    <div class="page-hero-text">
      <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp; <?php the_title(); ?></div>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
</section>
<section class="section section-bg-white" style="padding-top:40px;">
  <div class="section-inner">
    <div class="article-body"><?php the_content(); ?></div>
  </div>
</section>
<?php get_footer(); ?>
