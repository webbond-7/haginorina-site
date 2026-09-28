<?php
/**
 * ニュース記事
 */
get_header();
the_post();
$hn_cats = get_the_category();
$hn_cat  = $hn_cats ? $hn_cats[0] : null;
?>
<!-- PAGE HERO (single article header) -->
<section class="page-hero">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:80px;left:-60px;width:200px;height:200px;background:var(--color-yellow-soft);"></span>
  <span class="deco deco-ring float-medium" aria-hidden="true" style="position:absolute;bottom:60px;right:6%;width:60px;height:60px;border-color:var(--color-red);"></span>
  <span class="deco deco-circle float-fast" aria-hidden="true" style="position:absolute;top:160px;right:18%;width:24px;height:24px;background:var(--color-red);"></span>

  <div class="page-hero-inner">
    <div class="page-hero-text">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp;
        <a href="<?php echo esc_url( hn_url( 'news' ) ); ?>">NEWS</a> &nbsp;/&nbsp;
<?php if ( $hn_cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $hn_cat ) ); ?>"><?php echo esc_html( $hn_cat->name ); ?></a> &nbsp;/&nbsp;
<?php endif; ?>
        詳細
      </div>
      <div class="article-meta">
        <span><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
<?php if ( $hn_cat ) : ?>
        <span class="news-tag <?php echo esc_attr( hn_tag_class( $hn_cat->slug ) ); ?>"><?php echo esc_html( $hn_cat->name ); ?></span>
<?php endif; ?>
      </div>
      <h1 style="font-family:var(--font-jp);font-size:clamp(28px,4vw,44px);color:var(--color-text);line-height:1.4;">
        <?php the_title(); ?>
      </h1>
<?php if ( has_excerpt() ) : ?>
      <p style="margin-top:18px;"><?php echo esc_html( get_the_excerpt() ); ?></p>
<?php endif; ?>
    </div>
    <div class="page-hero-photo">
<?php
if ( has_post_thumbnail() ) {
	echo hn_cover_img( get_the_ID(), 'hn-large' );
} else {
	echo '<img src="' . hn_asset( 'images/g04.jpg' ) . '" alt="" style="object-position: 50% 21%;" />';
}
?>
    </div>
  </div>
</section>

<!-- ARTICLE BODY -->
<section class="section section-bg-white" style="padding-top:60px;">
  <div class="section-inner">

    <div class="article-body reveal">
      <?php the_content(); ?>
    </div>

    <!-- Share -->
    <div class="article-share reveal">
      <span>SHARE</span>
<?php
$hn_u = rawurlencode( get_permalink() );
$hn_t = rawurlencode( get_the_title() . '｜はぎのりな' );
?>
      <a href="https://x.com/intent/post?url=<?php echo $hn_u; ?>&amp;text=<?php echo $hn_t; ?>" target="_blank" rel="noopener" aria-label="Xでシェア"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $hn_u; ?>" target="_blank" rel="noopener" aria-label="Facebookでシェア"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
      <a href="https://social-plugins.line.me/lineit/share?url=<?php echo $hn_u; ?>" target="_blank" rel="noopener" aria-label="LINEで送る"><i class="fa-brands fa-line" aria-hidden="true"></i></a>
      <a href="<?php the_permalink(); ?>" data-copy-url aria-label="URLをコピー"><i class="fa-solid fa-link" aria-hidden="true"></i></a>
    </div>

    <!-- Prev / Next -->
    <div class="article-nav reveal">
<?php
$hn_prev = get_previous_post();
$hn_next = get_next_post();
if ( $hn_prev ) :
	?>
      <a href="<?php echo esc_url( get_permalink( $hn_prev ) ); ?>" class="prev">
        <span class="dir"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> PREV</span>
        <span class="title"><?php echo esc_html( get_the_title( $hn_prev ) ); ?></span>
      </a>
<?php else : ?>
      <span></span>
<?php endif; ?>
<?php if ( $hn_next ) : ?>
      <a href="<?php echo esc_url( get_permalink( $hn_next ) ); ?>" class="next">
        <span class="dir">NEXT <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></span>
        <span class="title"><?php echo esc_html( get_the_title( $hn_next ) ); ?></span>
      </a>
<?php endif; ?>
    </div>

    <!-- Back to news -->
    <div style="text-align:center;margin-top:48px;">
      <a class="nav-pill" href="<?php echo esc_url( hn_url( 'news' ) ); ?>" style="background:var(--color-yellow);">
        <i class="fa-solid fa-chevron-left" style="margin-right:8px;"></i> ALL NEWS
      </a>
    </div>

  </div>
</section>
<script>
  // URLをコピー
  document.querySelectorAll('[data-copy-url]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      if (!navigator.clipboard) return;
      e.preventDefault();
      navigator.clipboard.writeText(a.href).then(function () { a.setAttribute('aria-label', 'コピーしました'); a.style.color = 'var(--color-red)'; });
    });
  });
</script>

<?php get_footer(); ?>
