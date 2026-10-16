<?php
/**
 * Encart publicitaire : publicités actives (rotation si plusieurs), sinon offre « Votre publicité ici ».
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'sections' );
if ( ! $o['ads_enabled'] ) {
	return;
}

$ads = array();
if ( ocl_core_active() ) {
	foreach ( ocl_core_get_items( 'ocl_publicite' ) as $ad ) {
		if ( has_post_thumbnail( $ad ) ) {
			$ads[] = $ad;
		}
	}
}

if ( $ads ) :
	?>
	<aside class="ad-banner reveal" aria-label="<?php echo esc_attr( $o['ads_label'] ? $o['ads_label'] : __( 'Publicité', 'oc-lisala' ) ); ?>"<?php echo count( $ads ) > 1 ? ' data-rotate' : ''; ?>>
		<?php foreach ( $ads as $i => $ad ) : ?>
			<?php
			$url     = ocl_core_field( $ad->ID, 'url' );
			$new_tab = '1' === ocl_core_field( $ad->ID, 'new_tab' );
			$img     = get_the_post_thumbnail(
				$ad,
				'full',
				array(
					'class'   => 'ad-banner__img',
					'alt'     => get_the_title( $ad ),
					'loading' => 'lazy',
					'sizes'   => '(max-width: 1240px) 100vw, 1180px',
				)
			);
			?>
			<div class="ad-banner__item<?php echo 0 === $i ? ' is-current' : ''; ?>"<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>>
				<?php if ( $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" rel="sponsored noopener"<?php echo $new_tab ? ' target="_blank"' : ''; ?><?php echo 0 === $i ? '' : ' tabindex="-1"'; ?>><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php else : ?>
					<?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		<?php if ( $o['ads_label'] ) : ?>
			<span class="ad-banner__tag"><?php echo esc_html( $o['ads_label'] ); ?></span>
		<?php endif; ?>
	</aside>
	<?php
elseif ( $o['ads_fallback'] && ( $o['ads_title'] || $o['ads_text'] ) ) :
	?>
	<aside class="ad-slot reveal" aria-label="<?php echo esc_attr( $o['ads_label'] ? $o['ads_label'] : __( 'Espace publicitaire', 'oc-lisala' ) ); ?>">
		<div class="ad-slot__text">
			<?php if ( $o['ads_label'] ) : ?>
				<p class="ad-slot__label"><?php echo esc_html( $o['ads_label'] ); ?></p>
			<?php endif; ?>
			<?php if ( $o['ads_title'] ) : ?>
				<p class="ad-slot__title"><?php echo esc_html( $o['ads_title'] ); ?></p>
			<?php endif; ?>
			<?php if ( $o['ads_text'] ) : ?>
				<p class="ad-slot__sub"><?php echo esc_html( $o['ads_text'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php echo ocl_button( $o['ads_btn_label'], $o['ads_btn'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</aside>
	<?php
endif;
