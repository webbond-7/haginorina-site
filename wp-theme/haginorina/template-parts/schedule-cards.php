<?php
/**
 * ニュースページの「公演予定」カード(これからの公演を近い順に3件)
 */
$items = hn_upcoming_schedules( 3 );
if ( ! $items ) : ?>
    <p style="color:var(--color-text-sub);">次の公演は、決まりしだいお知らせします。</p>
<?php
	return;
endif;
?>
    <div class="schedule-grid">
<?php foreach ( $items as $i => $p ) :
	$s = hn_schedule( $p );
	$d = hn_date( $s['start'] );
	?>
      <a class="schedule-card reveal" href="<?php echo esc_url( hn_schedule_link( $s ) ); ?>"<?php echo $i ? ' style="transition-delay:.' . ( $i * 8 ) . 's"' : ''; ?>>
        <div class="date"><span class="d"><?php echo esc_html( $d ? $d->format( 'm' ) : '' ); ?></span><span class="m">/ <?php echo esc_html( $d ? strtoupper( $d->format( 'M Y' ) ) : '' ); ?></span></div>
        <h3><?php echo esc_html( get_the_title( $p ) ); ?></h3>
        <p class="meta"><?php echo esc_html( implode( ' ／ ', array_filter( array( $s['when'] ? $s['when'] : hn_range_text( $s['start'], $s['end'] ), $s['venue'], $s['part'] ) ) ) ); ?></p>
      </a>
<?php endforeach; ?>
    </div>
