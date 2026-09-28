<?php
/**
 * 役のカード1枚(ループの中で使う)
 */
$id = get_the_ID();
$m  = function ( $k, $default = '' ) use ( $id ) {
	$v = get_post_meta( $id, $k, true );
	return '' !== $v ? $v : $default;
};
$rows = array(
	array( $m( '_hn_l1', '期間' ), $m( '_hn_period' ) ),
	array( $m( '_hn_l2', '劇場' ), $m( '_hn_venue' ) ),
);
?>
        <button class="role-card" type="button" aria-label="<?php echo esc_attr( get_the_title() ); ?>のカードを裏返す">
          <span class="flip">
            <span class="face front"><span class="ph"><?php echo hn_cover_img( $id, 'hn-large' ); ?></span><span class="name"><?php the_title(); ?><small><?php echo esc_html( $m( '_hn_sub' ) ); ?></small></span></span>
            <span class="face back"><span class="paper"><span class="kind"><?php echo esc_html( $m( '_hn_kind', 'ROLE' ) ); ?></span><strong><?php echo esc_html( $m( '_hn_work' ) ); ?></strong><dl>
              <?php foreach ( $rows as $r ) : if ( '' === $r[1] ) { continue; } ?><dt><?php echo esc_html( $r[0] ); ?></dt><dd><?php echo esc_html( $r[1] ); ?></dd><?php endforeach; ?>
            </dl></span></span>
          </span>
        </button>
