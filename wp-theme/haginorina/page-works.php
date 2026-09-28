<?php
/**
 * 活動紹介(固定ページ works)
 */
get_header();
?>
<!-- PAGE HERO -->
<section class="page-hero">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:80px;left:-60px;width:200px;height:200px;background:var(--color-yellow-soft);"></span>
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;top:120px;right:6%;width:80px;height:80px;background:var(--color-yellow);"></span>
  <span class="deco deco-circle float-fast" aria-hidden="true" style="position:absolute;bottom:30px;right:18%;width:24px;height:24px;background:var(--color-red);"></span>
  <span class="deco deco-ring float-medium" aria-hidden="true" style="position:absolute;bottom:50px;left:20%;width:60px;height:60px;border-color:var(--color-red-soft);"></span>

  <div class="page-hero-inner">
    <div class="page-hero-text">
      <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp; WORKS</div>
      <h1>
        <span class="jp">WORKS — 活動紹介</span>
        Works.
      </h1>
      <p>舞台・映像・モデル。<br>ジャンルの境界をやわらかく行き来しながら、その場のための表現を届けています。</p>
    </div>
    <div class="page-hero-photo">
      <img style="object-position: 21% 50%;" src="<?php echo hn_asset( 'images/g06.jpg' ); ?>" alt="はぎのりな ポートレート" />
    </div>
  </div>
</section>

<!-- WORK SECTIONS -->
<section class="section section-bg-soft">
  <div class="section-inner">
    <div class="works-grid works-grid--3col">

      <article class="work-card reveal" style="padding:48px 40px;">
        <span class="work-num">01 — STAGE</span>
        <h3 style="font-size:32px;margin-top:18px;">舞台</h3>
        <span class="work-en">Theater / Stage Acting</span>
        <p style="margin-top:24px;">舞台に出演するほか、脚本や演出も手がけています。<br>客席のすぐそばで生まれる、その日かぎりの時間を大切にしています。</p>
        <p class="work-list-label">主な出演作</p>
        <ul class="work-list">
<?php
hn_work_list(
	'_hn_list_stage',
	array(
		'「グノーシア ザ・ライブプレイングシアター」コメット役（2026）',
		'イルカ団!!「Q.T!!!!」琴音役（2026）',
		'みかのり企画『Talk it over』出演・脚本・演出（2026）',
		'イルカ団!!「アルセーヌ・ルパン!!!!」緑川夫人役（2026）',
		'イルカ団!!『天爛のパティシエ』美緒役（2025）',
		'「人狼 ザ・ライブプレイングシアター」キンバリー役 ほか',
	)
);
?>
        </ul>
      </article>

      <article class="work-card reveal" style="padding:48px 40px;transition-delay:.08s;">
        <span class="work-num">02 — FILM</span>
        <h3 style="font-size:32px;margin-top:18px;">映像</h3>
        <span class="work-en">TV / Streaming</span>
        <p style="margin-top:24px;">人狼ゲームの配信番組にプレイヤーとして出演するほか、番組のゲストや動画にも出演しています。</p>
        <p class="work-list-label">主な出演作</p>
        <ul class="work-list">
<?php
hn_work_list(
	'_hn_list_film',
	array(
		'「アルティメット人狼」（配信）',
		'人狼TLPT公式番組「セブンスエデン」ゲスト（2026）',
		'TikTok「ラブタイプ劇場」（2026）',
	)
);
?>
        </ul>
      </article>

      <article class="work-card reveal" style="padding:48px 40px;transition-delay:.16s;">
        <span class="work-num">03 — MODEL</span>
        <h3 style="font-size:32px;margin-top:18px;">モデル</h3>
        <span class="work-en">Model / Photoshoot</span>
        <p style="margin-top:24px;">たまにモデルとして撮影に参加しています。<br>衣装と空気感に合わせて、別の自分でいる楽しさがあります。ルックブック・コンセプト撮影・ECなど。</p>
        <p class="work-list-label">撮影の内容</p>
        <ul class="work-list">
<?php
hn_work_list(
	'_hn_list_model',
	array(
		'スチール・動画撮影',
		'公式LINEメンバーシップ限定の撮影会',
	)
);
?>
        </ul>
      </article>

    </div>
  </div>
</section>

<!-- ROLES: 役のカード -->
<section class="section section-bg-white" id="roles">
  <div class="section-inner">
    <h2 class="section-title reveal" style="margin-bottom:40px;">
      <span class="jp">ROLES — これまでの役</span>
      Roles.
    </h2>
    <div class="role-cards reveal">
<?php
$hn_roles = new WP_Query( array( 'post_type' => 'hn_role', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
while ( $hn_roles->have_posts() ) :
	$hn_roles->the_post();
	get_template_part( 'template-parts/role-card' );
endwhile;
wp_reset_postdata();
?>
    </div>
    <p class="role-cards-hint">カードをタップすると、作品がわかります</p>
  </div>
</section>

<!-- CTA band -->
<section class="section section-bg-white" style="padding-top:60px;padding-bottom:80px;">
  <div class="section-inner reveal" style="text-align:center;">
    <h2 class="section-title" style="text-align:center;margin-bottom:20px;">
      <span class="jp" style="text-align:center;">CONTACT</span>
      Let's create together.
    </h2>
    <p style="color:var(--color-text-sub);max-width:540px;margin:0 auto 32px;line-height:2;">
      表現に関わるご相談・ご依頼は、フォームよりお気軽にどうぞ。
    </p>
    <a class="hero-cta" href="<?php echo esc_url( hn_url( 'contact' ) ); ?>">
      <span>お問い合わせはこちら</span>
      <span class="arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"/></svg></span>
    </a>
  </div>
</section>

<?php get_footer(); ?>
