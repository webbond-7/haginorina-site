<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <script>
  (function () {
    try {
      if (new URLSearchParams(location.search).has('loader')) {
        sessionStorage.removeItem('h_loaded');
        return;
      }
      if (sessionStorage.getItem('h_loaded')) document.documentElement.classList.add('no-loader');
    } catch (e) {}
  })();
  </script>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- SITE LOADER -->
<div class="site-loader" id="siteLoader" aria-hidden="true">
  <div class="buddy-wrap">
    <img class="buddy" src="<?php echo hn_asset( 'icons/loader-buddy.png' ); ?>" alt="" />
    <div class="buddy-shadow"></div>
  </div>
  <div class="label" aria-label="Now Loading">
    <span>N</span><span>o</span><span>w</span><span>&nbsp;</span><span>L</span><span>o</span><span>a</span><span>d</span><span>i</span><span>n</span><span>g</span><span>.</span><span>.</span><span>.</span>
  </div>
</div>


<!-- ============ HEADER ============ -->
<header class="site-header">
  <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Haginorina Official Fan Site">
    <div>
      <span class="logo-mark">
        Haginorina
        <img class="tulip" src="<?php echo hn_asset( 'icons/tulip.png' ); ?>" alt="" aria-hidden="true" />
      </span>
      <span class="logo-sub">Official Fan Site</span>
    </div>
  </a>

  <nav class="main-nav" aria-label="グローバルナビゲーション">
    <a class="nav-pill<?php echo hn_nav_active( 'works' ); ?>" href="<?php echo esc_url( hn_url( 'works' ) ); ?>">活動紹介</a>
    <a class="nav-pill<?php echo hn_nav_active( 'gallery' ); ?>" href="<?php echo esc_url( hn_url( 'gallery' ) ); ?>">Photo / Gallery</a>
    <a class="nav-pill<?php echo hn_nav_active( 'news' ); ?>" href="<?php echo esc_url( hn_url( 'news' ) ); ?>">News / Info</a>
    <a class="nav-pill<?php echo hn_nav_active( 'contact' ); ?>" href="<?php echo esc_url( hn_url( 'contact' ) ); ?>">Contact &amp; SNS</a>
  </nav>

  <button class="nav-toggle" aria-label="メニュー" aria-controls="mobileNav" aria-expanded="false">
    <span></span>
  </button>
</header>

<aside id="mobileNav" class="mobile-nav" aria-label="モバイルナビゲーション">
  <a href="<?php echo esc_url( hn_url( 'works' ) ); ?>">活動紹介 <span class="arrow">→</span></a>
  <a href="<?php echo esc_url( hn_url( 'gallery' ) ); ?>">Photo / Gallery <span class="arrow">→</span></a>
  <a href="<?php echo esc_url( hn_url( 'news' ) ); ?>">News / Info <span class="arrow">→</span></a>
  <a href="<?php echo esc_url( hn_url( 'contact' ) ); ?>">Contact &amp; SNS <span class="arrow">→</span></a>
</aside>

