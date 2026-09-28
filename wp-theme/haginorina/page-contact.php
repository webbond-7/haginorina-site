<?php
/**
 * Contact & SNS(固定ページ contact)
 */
get_header();
?>
  <style>
    .contact-form { display: grid; gap: 20px; }
    .field { display: grid; gap: 8px; }
    .field label { font-weight: 700; font-size: 13px; letter-spacing: 0.06em; color: var(--color-text); }
    .field label .req { color: var(--color-red); margin-left: 6px; font-size: 11px; }
    .field input, .field select, .field textarea {
      font-family: inherit; font-size: 15px;
      padding: 16px 20px;
      border: 1.5px solid var(--color-border);
      border-radius: var(--radius-md);
      background: #fff;
      transition: border-color .2s ease, box-shadow .2s ease;
      width: 100%;
    }
    .field input:focus, .field select:focus, .field textarea:focus {
      outline: none;
      border-color: var(--color-red);
      box-shadow: 0 0 0 4px rgba(230,57,70,0.10);
    }
    .field textarea { resize: vertical; min-height: 140px; }
    .submit-row { display: flex; justify-content: center; margin-top: 12px; }
    .request-list {
      display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;
      margin-top: 28px;
    }
    .request-item {
      display: flex; align-items: center; gap: 14px;
      padding: 18px 20px;
      background: var(--color-yellow-soft);
      border-radius: var(--radius-lg);
      font-weight: 700; font-size: 14px;
    }
    .request-item .num {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--color-red); color: #fff;
      display: inline-flex; align-items: center; justify-content: center;
      font-family: var(--font-en); font-size: 13px;
    }
    @media (max-width: 767px) {
      .request-list { grid-template-columns: 1fr; }
    }
    .hn-trap { position: absolute !important; left: -9999px !important; width: 1px; height: 1px; overflow: hidden; }
    .contact-notice { margin: 0 0 20px; padding: 14px 18px; border-radius: 16px; font-weight: 700; line-height: 1.8; }
    .contact-notice.is-ok { background: var(--color-yellow-soft); color: var(--color-text); }
    .contact-notice.is-error { background: var(--color-red-soft); color: var(--color-red); }
  </style>


<section class="page-hero">
  <span class="deco deco-circle float-soft" aria-hidden="true" style="position:absolute;top:80px;right:-80px;width:240px;height:240px;background:var(--color-yellow-soft);"></span>
  <span class="deco deco-circle float-medium" aria-hidden="true" style="position:absolute;top:200px;left:6%;width:60px;height:60px;background:var(--color-red);opacity:.2;"></span>
  <span class="deco deco-stripe float-soft" aria-hidden="true" style="position:absolute;bottom:50px;right:20%;width:120px;height:120px;transform:rotate(-12deg);opacity:.5;"></span>

  <div class="page-hero-inner">
    <div class="page-hero-text">
      <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a> &nbsp;/&nbsp; CONTACT &amp; SNS</div>
      <h1>
        <span class="jp">CONTACT &amp; SNS — お問い合わせ</span>
        Get in touch.
      </h1>
      <p>お仕事のご依頼・取材・コラボなど、お気軽にご連絡ください。<br>SNSはDMでもお受けしています。</p>
    </div>
    <div class="page-hero-photo">
      <img src="<?php echo hn_asset( 'images/g09.jpg' ); ?>" alt="はぎのりな ポートレート" style="object-position: 50% 24%;" />
    </div>
  </div>
</section>

<!-- REQUEST TYPES -->
<section class="section section-bg-soft" style="padding-bottom:60px;">
  <div class="section-inner reveal">
    <h2 class="section-title" style="font-size:36px;">
      <span class="jp">お受けしている内容</span>
      How can I help?
    </h2>
    <div class="request-list">
      <div class="request-item"><span class="num">01</span>舞台・朗読 出演</div>
      <div class="request-item"><span class="num">02</span>映像・短編映画・MV 出演</div>
      <div class="request-item"><span class="num">03</span>モデル・撮影・ルックブック</div>
      <div class="request-item"><span class="num">04</span>イベント・トーク 出演</div>
      <div class="request-item"><span class="num">05</span>その他コラボのご相談</div>
    </div>
  </div>
</section>

<!-- FORM + SNS -->
<section class="section section-bg-white" style="padding-top:40px;">
  <div class="section-inner">
    <div class="contact-grid">
      <div class="contact-card reveal" id="contact-form">
        <h3>お問い合わせフォーム</h3>
        <p>必要事項をご入力のうえ、送信してください。<br>内容を確認のうえ、3〜7日以内にご返信いたします。</p>

<?php
$hn_notice = hn_contact_notice();
if ( $hn_notice ) :
	?>
        <p class="contact-notice is-<?php echo esc_attr( $hn_notice[0] ); ?>" role="<?php echo 'ok' === $hn_notice[0] ? 'status' : 'alert'; ?>"><?php echo esc_html( $hn_notice[1] ); ?></p>
<?php endif; ?>
<?php if ( ! $hn_notice || 'ok' !== $hn_notice[0] ) : ?>
        <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <input type="hidden" name="action" value="hn_contact" />
          <input type="hidden" name="hn_ts" value="<?php echo esc_attr( time() ); ?>" />
          <?php wp_nonce_field( 'hn_contact', 'hn_contact_nonce' ); ?>
          <div class="field hn-trap" aria-hidden="true">
            <label for="hn_website">ウェブサイト(入力しないでください)</label>
            <input type="text" id="hn_website" name="hn_website" tabindex="-1" autocomplete="off" />
          </div>
          <div class="field">
            <label for="hn_name">お名前 <span class="req">必須</span></label>
            <input type="text" id="hn_name" name="hn_name" required maxlength="100" autocomplete="name" placeholder="山田 太郎" />
          </div>
          <div class="field">
            <label for="hn_email">メールアドレス <span class="req">必須</span></label>
            <input type="email" id="hn_email" name="hn_email" required autocomplete="email" placeholder="you@example.com" />
          </div>
          <div class="field">
            <label for="hn_kind">ご依頼内容</label>
            <select id="hn_kind" name="hn_kind">
<?php foreach ( hn_contact_kinds() as $k ) : ?>
              <option><?php echo esc_html( $k ); ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="hn_message">メッセージ <span class="req">必須</span></label>
            <textarea id="hn_message" name="hn_message" required maxlength="5000" placeholder="ご依頼の概要・スケジュール・媒体などをご記入ください。"></textarea>
          </div>
          <div class="submit-row">
            <button type="submit" class="hero-cta" style="border:0;">
              <span>送信する</span>
              <span class="arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"/></svg></span>
            </button>
          </div>
        </form>
<?php endif; ?>
      </div>

      <div class="sns-block reveal">
        <h3 style="font-family:var(--font-en);color:var(--color-red);font-size:24px;margin-bottom:8px;">Follow me on SNS</h3>
        <p style="color:var(--color-text-sub);margin-bottom:20px;line-height:2;">日常や活動の様子は、SNSで発信しています。<br>気軽にフォローしてください。</p>
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
