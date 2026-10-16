<?php
/**
 * Section « Partenaires » : bandeau rouge, avantages, logos des partenaires officiels.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'sections' );
if ( ! $o['partners_enabled'] ) {
	return;
}
$partners = array();
if ( ocl_core_active() ) {
	foreach ( ocl_core_get_items( 'ocl_partenaire' ) as $partner ) {
		if ( has_post_thumbnail( $partner ) ) {
			$partners[] = $partner;
		}
	}
}
$bg       = ocl_image( $o['partners_image'], 'ocl-hero', array( 'loading' => 'lazy', 'sizes' => '100vw', 'alt' => '' ) );
$main_btn = ocl_button( $o['partners_btn_label'], $o['partners_btn'] );
$logo_btn = ocl_button( $o['logos_btn_label'], $o['partners_btn'], 'btn btn--dark' );
?>
<section class="section section--partners" id="partenaires"<?php echo ocl_labelledby( $o['partners_title'], 'partners-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="partners-hero">
		<div class="partners-hero__media media<?php echo $bg ? '' : ' is-missing'; ?>" aria-hidden="true">
			<?php echo $bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="container partners-hero__inner">
			<div class="reveal">
				<?php if ( $o['partners_eyebrow'] ) : ?>
					<p class="eyebrow eyebrow--light"><?php echo esc_html( $o['partners_eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $o['partners_title'] ) : ?>
					<h2 class="section-title section-title--light" id="partners-title"><?php echo esc_html( $o['partners_title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( $o['partners_lead'] ) : ?>
					<p class="partners-hero__lead"><?php echo esc_html( $o['partners_lead'] ); ?></p>
				<?php endif; ?>
				<?php echo $main_btn; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>

	<div class="container">
		<?php if ( ! empty( $o['benefits'] ) ) : ?>
			<ul class="benefits">
				<?php foreach ( array_values( $o['benefits'] ) as $i => $benefit ) : ?>
					<li class="benefit reveal">
						<span class="benefit__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<?php if ( $benefit['title'] ) : ?>
							<h3><?php echo esc_html( $benefit['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $benefit['text'] ) : ?>
							<p><?php echo esc_html( $benefit['text'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $partners || $logo_btn ) : ?>
			<div class="partner-logos reveal<?php echo empty( $o['benefits'] ) ? ' partner-logos--first' : ''; ?>">
				<?php if ( $partners ) : ?>
					<?php if ( $o['logos_title'] ) : ?>
						<h3 class="partner-logos__title"><?php echo esc_html( $o['logos_title'] ); ?></h3>
					<?php endif; ?>
					<ul class="partner-logos__list">
						<?php foreach ( $partners as $partner ) : ?>
							<?php
							$url  = ocl_core_field( $partner->ID, 'url' );
							$name = get_the_title( $partner );
							$logo = get_the_post_thumbnail(
								$partner,
								'medium',
								array(
									'class'   => 'partner-logo__img',
									'alt'     => $name,
									'loading' => 'lazy',
								)
							);
							?>
							<li class="partner-logo">
								<?php if ( $url ) : ?>
									<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener sponsored" title="<?php echo esc_attr( $name ); ?>"><?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
								<?php else : ?>
									<?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php echo $logo_btn; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</section>
