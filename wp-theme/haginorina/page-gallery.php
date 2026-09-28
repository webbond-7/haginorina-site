<?php
/**
 * Photo / Gallery(固定ページ gallery)
 */
get_header();
?>
  <style>
    .gallery-filter { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:40px; }
    .gallery-filter button {
      padding:10px 20px; border-radius:999px; background:#fff; font-weight:700; font-size:13px;
      letter-spacing:0.08em; box-shadow: 0 4px 14px rgba(0,0,0,0.04);
      transition: background .25s ease, color .25s ease, transform .25s ease;
    }
    .gallery-filter button.is-active { background: var(--color-red); color:#fff; }
    .gallery-filter button:hover { transform: translateY(-2px); }
    .gallery-grid { grid-auto-flow: dense; }
    .gallery-item[hidden] { display: none; }
  </style>


<section class="page-hero">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:60px;right:-80px;width:220px;height:220px;background:var(--color-yellow-soft);"></span>
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;top:160px;left:8%;width:48px;height:48px;background:var(--color-red);opacity:.18;"></span>
  <span class="deco deco-ring float-medium" aria-hidden="true" style="position:absolute;bottom:60px;right:12%;width:70px;height:70px;border-color:var(--color-yellow);"></span>

  <div class="page-hero-inner">
    <div class="page-hero-text">
      <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp; PHOTO / GALLERY</div>
      <h1>
        <span class="jp">PHOTO / GALLERY — 写真</span>
        Gallery.
      </h1>
      <p>舞台・撮影・オフショットなど、これまでの瞬間たち。<br>カテゴリで絞り込んでご覧いただけます。</p>
    </div>
    <div class="page-hero-photo">
      <img src="<?php echo hn_asset( 'images/g04.jpg' ); ?>" alt="はぎのりな 写真" style="object-position: 50% 21%;" />
    </div>
  </div>
</section>

<section class="section section-bg-soft">
  <div class="section-inner">
<?php
$hn_terms = get_terms( array( 'taxonomy' => 'hn_photo_cat', 'hide_empty' => true, 'orderby' => 'term_id' ) );
if ( $hn_terms && ! is_wp_error( $hn_terms ) ) :
	?>
    <div class="gallery-filter reveal" role="group" aria-label="写真の絞り込み">
      <button type="button" class="is-active" data-filter="all" aria-pressed="true">ALL</button>
<?php foreach ( $hn_terms as $t ) : ?>
      <button type="button" data-filter="<?php echo esc_attr( $t->slug ); ?>" aria-pressed="false"><?php echo esc_html( $t->name ); ?></button>
<?php endforeach; ?>
    </div>
<?php endif; ?>

    <div class="gallery-grid reveal" data-lightbox>
<?php
// 最初に出す枚数(残りは「もっと見る」で24枚ずつ)
define( 'HN_GALLERY_PAGE', 24 );
// 大きさの並び(12枚で1周)
$hn_spans  = array( 'span-2x2', '', '', '', '', 'span-2x1', '', '', 'span-1x2', '', '', 'span-2x2' );
$hn_photos = get_posts( array( 'post_type' => 'hn_photo', 'numberposts' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
foreach ( $hn_photos as $i => $p ) :
	$cats    = wp_get_object_terms( $p->ID, 'hn_photo_cat', array( 'fields' => 'slugs' ) );
	$caption = get_post_meta( $p->ID, '_hn_caption', true );
	$full    = wp_get_attachment_image_url( get_post_thumbnail_id( $p->ID ), 'full' );
	?>
      <a class="gallery-item <?php echo esc_attr( $hn_spans[ $i % count( $hn_spans ) ] ); ?>"<?php echo $i >= HN_GALLERY_PAGE ? ' hidden' : ''; ?> href="<?php echo esc_url( $full ); ?>" data-cats="<?php echo esc_attr( implode( ' ', (array) $cats ) ); ?>" data-caption="<?php echo esc_attr( $caption ); ?>"><?php echo hn_cover_img( $p->ID, 'hn-large', 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;' ); ?></a>
<?php endforeach; ?>
    </div>
<?php if ( count( $hn_photos ) > HN_GALLERY_PAGE ) : ?>
    <div style="margin-top:60px;text-align:center;">
      <button type="button" class="hero-cta" data-more style="border:0;">
        <span>MORE PHOTOS</span>
        <span class="arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
      </button>
    </div>
<?php endif; ?>
<?php if ( ! $hn_photos ) : ?>
    <p style="color:var(--color-text-sub);">写真は準備中です。</p>
<?php endif; ?>
  </div>
</section>
<script>
  // 分類で絞り込む／「もっと見る」で24枚ずつ足す
  (function () {
    var PAGE = 24, shown = PAGE, filter = 'all';
    var btns = document.querySelectorAll('.gallery-filter button');
    var items = Array.prototype.slice.call(document.querySelectorAll('.gallery-grid .gallery-item'));
    var more = document.querySelector('[data-more]');
    function apply() {
      items.forEach(function (it, i) {
        var cats = (it.getAttribute('data-cats') || '').split(' ');
        it.hidden = filter === 'all' ? i >= shown : cats.indexOf(filter) === -1;
      });
      if (more) { more.parentNode.hidden = !(filter === 'all' && shown < items.length); }
    }
    btns.forEach(function (b) {
      b.addEventListener('click', function () {
        filter = b.getAttribute('data-filter');
        btns.forEach(function (x) { x.classList.toggle('is-active', x === b); x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
        apply();
      });
    });
    if (more) {
      more.addEventListener('click', function () { shown += PAGE; apply(); });
    }
  })();
</script>

<?php get_footer(); ?>
