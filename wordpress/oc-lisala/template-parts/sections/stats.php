<?php
/**
 * Bandeau de chiffres rouge sous la bannière.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'hero' );
if ( ! $o['stats_enabled'] || empty( $o['stats'] ) ) {
	return;
}
?>
<section class="stats" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: nom du club. */ __( '%s en quelques repères', 'oc-lisala' ), ocl_opt( 'general', 'short_name' ) ) ); ?>">
	<div class="container stats__grid" style="--stats-cols:<?php echo (int) min( count( $o['stats'] ), 6 ); ?>">
		<?php foreach ( $o['stats'] as $stat ) : ?>
			<div class="stat">
				<span class="stat__value">
					<?php
					$value = (string) $stat['value'];
					if ( '%' === substr( $value, -1 ) ) {
						echo esc_html( substr( $value, 0, -1 ) ) . '<small>%</small>';
					} else {
						echo esc_html( $value );
					}
					?>
				</span>
				<?php if ( '' !== $stat['label'] ) : ?>
					<span class="stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
