<?php
/**
 * トップの「次の舞台」の帯
 * 公演予定のうち、まだ終わっていない一番近いものを出す。無ければ帯ごと出さない。
 */
$next = hn_upcoming_schedules( 1 );
if ( ! $next ) {
	return;
}
$s = hn_schedule( $next[0] );
?>
<section class="next-band" aria-label="次の舞台">
  <div class="next-band-inner">
    <span class="next-card" aria-hidden="true">?</span>
    <span class="next-label">NEXT STAGE</span>
    <span class="next-date"><?php echo $s['when'] ? esc_html( $s['when'] ) : hn_band_date( $s['start'], $s['end'] ); // hn_band_date はエスケープ済み ?></span>
    <span class="next-title"><?php echo esc_html( get_the_title( $next[0] ) ); ?><?php if ( $s['part'] ) : ?> <?php echo esc_html( $s['part'] ); ?><?php endif; ?><?php if ( $s['venue'] ) : ?><em><?php echo esc_html( $s['venue'] ); ?></em><?php endif; ?></span>
    <a class="next-btn" href="<?php echo esc_url( hn_schedule_link( $s ) ); ?>">次の役に会いに行く <span aria-hidden="true">→</span></a>
  </div>
</section>
