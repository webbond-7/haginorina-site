<!-- ============ FOOTER ============ -->
<footer class="site-footer">
  <span class="deco deco-circle" aria-hidden="true" style="position:absolute;top:-100px;left:-80px;width:280px;height:280px;"></span>
  <span class="deco deco-circle" aria-hidden="true" style="position:absolute;bottom:-160px;right:-100px;width:320px;height:320px;background:rgba(244,211,94,0.12);"></span>

  <div class="footer-grid">
    <div class="footer-brand">
      <span class="logo-mark">
        Haginorina
        <img class="tulip" src="<?php echo hn_asset( 'icons/tulip.png' ); ?>" alt="" style="width:24px;height:24px;transform:translateY(4px);">
      </span>
      <span class="logo-sub">Official Fan Site</span>
      <p>表現することが大好きな、フリーの役者 / たまにモデル。<br>境界を感じて、日常を変えていく。</p>
    </div>
    <div class="footer-col">
      <h4>SITE MAP</h4>
      <ul>
        <li><a href="<?php echo esc_url( hn_url( 'works' ) ); ?>">活動紹介</a></li>
        <li><a href="<?php echo esc_url( hn_url( 'gallery' ) ); ?>">Photo / Gallery</a></li>
        <li><a href="<?php echo esc_url( hn_url( 'news' ) ); ?>">News / Info</a></li>
        <li><a href="<?php echo esc_url( hn_url( 'contact' ) ); ?>">Contact &amp; SNS</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>WORKS</h4>
      <ul>
        <li><a href="<?php echo esc_url( hn_url( 'works' ) ); ?>">舞台</a></li>
        <li><a href="<?php echo esc_url( hn_url( 'works' ) ); ?>">映像</a></li>
        <li><a href="<?php echo esc_url( hn_url( 'works' ) ); ?>">モデル</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>FOLLOW</h4>
      <ul>
        <li><a href="https://x.com/rinasa__n" target="_blank" rel="noopener">X (Twitter)</a></li>
        <li><a href="https://www.instagram.com/haginori02/" target="_blank" rel="noopener">Instagram</a></li>
        <li><a href="#">YouTube</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Haginorina. All Rights Reserved.</span>
    <span>Feel the Boundary, Transform the Ordinary.</span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
