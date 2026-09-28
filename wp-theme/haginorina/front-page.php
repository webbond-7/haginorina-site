<?php
/**
 * トップページ
 */
get_header();
?>
<!-- ============ HERO / FV ============ -->
<section class="hero" data-screen-label="01 FV" data-section="hero" aria-label="First View">

  <!-- Background decoration (aria-hidden) -->
  <span class="deco deco-circle hero-deco-1 float-soft" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-2 float-medium" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-3 float-soft" aria-hidden="true"></span>
  <span class="deco deco-ring hero-deco-4 float-medium" aria-hidden="true" style="border-color: var(--color-yellow);"></span>
  <span class="deco deco-circle hero-deco-5 float-soft" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-6 float-fast" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-7 float-medium" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-8 float-soft" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-9 float-medium" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-10 float-soft" aria-hidden="true"></span>
  <span class="deco deco-circle hero-deco-11 float-fast" aria-hidden="true"></span>
  <span class="deco deco-ring hero-deco-12 float-medium" aria-hidden="true" style="border-color: var(--color-red-soft);"></span>
  <span class="deco deco-stripe hero-stripe-1 float-soft" aria-hidden="true"></span>
  <span class="deco hero-dot-grid float-medium" aria-hidden="true"></span>

  <!-- Left: copy -->
  <div class="hero-text">
    <div class="hero-eyebrow">
      <span class="dot-cluster">
        <span class="dot l"></span>
        <span class="dot s"></span>
        <span class="dot m"></span>
      </span>
      <span>フリーの役者&nbsp;/&nbsp;たまにモデル</span>
    </div>

    <h1 class="hero-title" data-split-chars>はぎのりな</h1>

    <div class="hero-divider" aria-hidden="true"></div>

    <p class="hero-lead reveal">
      表現することが大好きです。<br />
      舞台や映像、いろんな場所で<br />
      皆さんと出会えますように。
    </p>

    <a class="hero-cta reveal" href="<?php echo esc_url( hn_url( 'news' ) ); ?>">
      <span>最新情報をチェック</span>
      <span class="arrow" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"/></svg>
      </span>
    </a>

    <div class="hero-sns reveal" aria-label="SNSリンク">
      <a class="sns-icon sns-x" href="https://x.com/rinasa__n" target="_blank" rel="noopener" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
      <a class="sns-icon sns-ig" href="https://www.instagram.com/haginori02/" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
      <a class="sns-icon sns-yt" href="#" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
    </div>
  </div>

  <!-- Right: visual -->
  <div class="hero-visual-wrap">
    <div class="hero-visual">
      <div class="hero-slide is-active"><?php echo hn_hero_img( 1 ); ?></div>
      <div class="hero-slide"><?php echo hn_hero_img( 2 ); ?></div>
      <div class="hero-slide"><?php echo hn_hero_img( 3 ); ?></div>

      <div class="slide-indicator" aria-label="メインビジュアル切替">
        <button class="is-active" aria-label="1枚目"></button>
        <button aria-label="2枚目"></button>
        <button aria-label="3枚目"></button>
      </div>
    </div>

    <div class="thank-you-badge" aria-hidden="true">
      <span class="script">Thank you</span>
      <span class="sub">for always<br>supporting me!</span>
      <img class="tulip" src="<?php echo hn_asset( 'icons/tulip.png' ); ?>" alt="" />
    </div>
  </div>

  <div class="scroll-cue" aria-hidden="true">
    <span class="line"></span>
    <span>SCROLL</span>
  </div>
</section>

<!-- ============ NEXT STAGE ============ -->
<?php get_template_part( 'template-parts/next-band' ); ?>

<!-- ============ ABOUT / CONCEPT ============ -->
<section class="section section-bg-white" data-section="about" aria-label="コンセプト">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:60px;right:-60px;width:180px;height:180px;background:var(--color-yellow-soft);opacity:.6;"></span>
  <span class="deco deco-ring float-medium" aria-hidden="true" style="position:absolute;bottom:80px;left:6%;width:80px;height:80px;border-color:var(--color-red-soft);"></span>

  <div class="section-inner reveal">
    <div class="section-head" style="align-items:flex-start;">
      <div>
        <h2 class="section-title">
          <span class="jp">CONCEPT — コンセプト</span>
          Feel the Boundary,<br>Transform the Ordinary.
        </h2>
      </div>
      <p class="section-lead">
        境界を感じて、日常を変えていく。<br>
        日常と非日常のあいだに立つ存在として、<br>
        舞台・映像・モデル活動を通して、<br>
        見る人の感情をすこし揺らす表現を届けます。
      </p>
    </div>
  </div>
</section>

<!-- ============ WORKS / 活動紹介 ============ -->
<section id="works" class="section section-bg-soft" data-section="works" aria-label="活動紹介">
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;top:80px;left:-80px;width:240px;height:240px;background:var(--color-yellow);opacity:.45;"></span>
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;bottom:0;right:-100px;width:280px;height:280px;background:var(--color-yellow-soft);"></span>

  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">WORKS — 活動紹介</span>
        <img src="<?php echo hn_asset( 'icons/tulip.png' ); ?>" class="tulip-small" alt="" />Works
      </h2>
      <p class="section-lead">
        舞台・映像・モデル──<br>
        ジャンルの境界をやわらかく行き来しながら、<br>
        その場のための表現を届けています。
      </p>
    </div>

    <div class="works-grid works-grid--3col">
      <article class="work-card reveal">
        <span class="work-num">01</span>
        <h3>舞台</h3>
        <span class="work-en">Stage / Theater</span>
        <p>舞台への出演のほか、脚本や演出も手がけています。客席のすぐそばで生まれる、その日かぎりの時間を大切に。</p>
      </article>
      <article class="work-card reveal" style="transition-delay:.08s">
        <span class="work-num">02</span>
        <h3>映像</h3>
        <span class="work-en">TV / Streaming</span>
        <p>人狼ゲームの配信番組にプレイヤーとして出演するほか、番組のゲストや動画にも出演しています。</p>
      </article>
      <article class="work-card reveal" style="transition-delay:.16s">
        <span class="work-num">03</span>
        <h3>モデル</h3>
        <span class="work-en">Model</span>
        <p>たまにモデルとして撮影に参加。衣装と空気感に合わせて、別の自分でいる楽しさがあります。</p>
      </article>
    </div>
    <p class="role-cards-hint reveal"><a href="<?php echo esc_url( hn_url( 'works' ) ); ?>#roles" style="color:var(--color-red);font-weight:700;">これまでの役をカードで見る →</a></p>
  </div>
</section>

<!-- ============ PROFILE ============ -->
<section class="section section-bg-tint" data-section="profile" aria-label="プロフィール">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:40px;right:8%;width:60px;height:60px;background:var(--color-red);opacity:.25;"></span>
  <span class="deco deco-stripe float-medium" aria-hidden="true" style="position:absolute;bottom:60px;right:-40px;width:160px;height:160px;transform:rotate(15deg);opacity:.6;"></span>

  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">PROFILE — プロフィール</span>
        About me.
      </h2>
    </div>

    <div class="profile-band reveal">
      <div class="profile-photo">
        <?php echo hn_profile_img(); ?>
      </div>
      <div class="profile-info">
        <dl>
          <dt>Name</dt><dd><?php echo esc_html( hn_profile( 'name' ) ); ?></dd>
          <dt>Birthday</dt><dd><?php echo esc_html( hn_profile( 'birthday' ) ); ?></dd>
          <dt>Activity</dt><dd><?php echo esc_html( hn_profile( 'activity' ) ); ?></dd>
          <dt>Field</dt><dd><?php echo esc_html( hn_profile( 'field' ) ); ?></dd>
          <dt>Status</dt><dd><?php echo esc_html( hn_profile( 'status' ) ); ?></dd>
        </dl>
        <p>
          <?php echo nl2br( esc_html( hn_profile( 'text' ) ) ); ?>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ============ NEWS preview ============ -->
<section class="section section-bg-white" data-section="news" aria-label="お知らせ">
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;top:0;left:30%;width:120px;height:120px;background:var(--color-yellow-soft);opacity:.7;"></span>

  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">NEWS / INFO — お知らせ</span>
        News.
      </h2>
      <a class="nav-pill" href="<?php echo esc_url( hn_url( 'news' ) ); ?>" style="background:var(--color-yellow);">ALL NEWS →</a>
    </div>

        <div class="news-list reveal">
<?php
$hn_news = get_posts( array( 'numberposts' => 4 ) );
if ( $hn_news ) {
	foreach ( $hn_news as $p ) {
		hn_news_item( $p );
	}
} else {
	echo '<p style="color:var(--color-text-sub);">お知らせは準備中です。</p>';
}
?>
    </div>
  </div>
</section>

<!-- ============ GALLERY preview ============ -->
<section class="section section-bg-soft" data-section="gallery" aria-label="ギャラリー">
  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">PHOTO / GALLERY — 写真</span>
        Gallery.
      </h2>
      <a class="nav-pill" href="<?php echo esc_url( hn_url( 'gallery' ) ); ?>" style="background:var(--color-yellow);">VIEW ALL →</a>
    </div>

    <div class="gallery-grid reveal">
<?php
$hn_spans  = array( 'span-2x2', '', '', '', '', 'span-2x1', '', '' );
$hn_photos = get_posts( array( 'post_type' => 'hn_photo', 'numberposts' => 8, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
foreach ( $hn_photos as $i => $p ) :
	?>
      <a class="gallery-item <?php echo esc_attr( $hn_spans[ $i ] ); ?>" href="<?php echo esc_url( hn_url( 'gallery' ) ); ?>"><?php echo hn_cover_img( $p->ID, 'hn-large', 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;' ); ?></a>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CONTACT ============ -->
<section class="section section-bg-tint" data-section="contact" aria-label="コンタクト">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:40px;left:-80px;width:200px;height:200px;background:var(--color-yellow);opacity:.4;"></span>
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;bottom:0;right:0;width:240px;height:240px;background:var(--color-red-soft);"></span>

  <div class="section-inner">
    <div class="section-head reveal">
      <h2 class="section-title">
        <span class="jp">CONTACT &amp; SNS — お問い合わせ</span>
        Get in touch.
      </h2>
    </div>

    <div class="contact-grid">
      <div class="contact-card reveal">
        <h3>お仕事のご依頼</h3>
        <p>
          舞台・映像・撮影・寄稿・コラボなど、表現に関わるご相談はフォームよりお気軽にお寄せください。<br>
          内容を確認のうえ、3〜7日以内にご返信いたします。
        </p>
        <a class="btn-primary" href="<?php echo esc_url( hn_url( 'contact' ) ); ?>">
          フォームを開く
          <span aria-hidden="true">→</span>
        </a>
      </div>

      <div class="sns-block reveal">
        <a class="sns-row" href="https://x.com/rinasa__n" target="_blank" rel="noopener">
          <span class="icon-wrap x"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></span>
          <span class="info"><span class="name">X (Twitter)</span><span class="handle">@rinasa__n</span></span>
          <span class="arrow">→</span>
        </a>
        <a class="sns-row" href="https://www.instagram.com/haginori02/" target="_blank" rel="noopener">
          <span class="icon-wrap ig"><i class="fa-brands fa-instagram" aria-hidden="true"></i></span>
          <span class="info"><span class="name">Instagram</span><span class="handle">@haginori02</span></span>
          <span class="arrow">→</span>
        </a>
        <a class="sns-row" href="#">
          <span class="icon-wrap yt"><i class="fa-brands fa-youtube" aria-hidden="true"></i></span>
          <span class="info"><span class="name">YouTube</span><span class="handle">準備中</span></span>
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
