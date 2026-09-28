<?php
/**
 * ページが見つからないとき
 */
get_header();
?>
<section class="page-hero">
  <div class="page-hero-inner" style="grid-template-columns:1fr;text-align:center;">
    <div class="page-hero-text" style="margin:0 auto;">
      <h1><span class="jp">404 — ページが見つかりません</span>Not found.</h1>
      <p style="margin:18px auto 32px;">お探しのページは、移動したか削除された可能性があります。</p>
      <a class="hero-cta" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span>トップへ戻る</span></a>
    </div>
  </div>
</section>
<?php get_footer(); ?>
