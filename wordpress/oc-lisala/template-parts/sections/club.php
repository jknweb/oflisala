<?php
/**
 * Rubrique « Le Club » : présentation, photos, sections sportives, encart publicitaire.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'club' );
if ( ! $o['enabled'] ) {
	return;
}
$main  = ocl_image( $o['image_main'], 'large', array( 'loading' => 'lazy', 'sizes' => '(max-width: 960px) 90vw, 560px' ) );
$small = ocl_image( $o['image_small'], 'medium_large', array( 'loading' => 'lazy', 'sizes' => '260px' ) );
$badge = trim( (string) $o['badge'] );
?>
<section class="section section--club" id="club"<?php echo ocl_labelledby( $o['title'], 'club-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container">
		<div class="editorial<?php echo ( $main || $small ) ? '' : ' editorial--text-only'; ?>">
			<div class="editorial__text reveal">
				<?php if ( '' !== $o['eyebrow'] ) : ?>
					<p class="eyebrow"><?php echo esc_html( $o['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $o['title'] ) : ?>
					<h2 class="section-title" id="club-title"><?php echo esc_html( $o['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $o['lead'] ) : ?>
					<p class="lead"><?php echo esc_html( $o['lead'] ); ?></p>
				<?php endif; ?>
				<?php echo wp_kses_post( wpautop( $o['content'] ) ); ?>
			</div>
			<?php if ( $main || $small ) : ?>
				<div class="editorial__media reveal">
					<?php if ( $main ) : ?>
						<figure class="media media--tall"><?php echo $main; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figure>
					<?php endif; ?>
					<?php if ( $small ) : ?>
						<figure class="media media--small"><?php echo $small; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figure>
					<?php endif; ?>
					<?php if ( '' !== $badge ) : ?>
						<span class="editorial__badge" aria-hidden="true"><?php echo nl2br( esc_html( $badge ) ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $o['disciplines'] ) ) : ?>
			<div class="disciplines">
				<?php foreach ( $o['disciplines'] as $discipline ) : ?>
					<article class="discipline reveal">
						<?php echo ocl_icon( $discipline['icon'], 'discipline__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statique. ?>
						<div>
							<?php if ( '' !== $discipline['label'] ) : ?>
								<p class="discipline__label"><?php echo esc_html( $discipline['label'] ); ?></p>
							<?php endif; ?>
							<h3 class="discipline__title"><?php echo esc_html( $discipline['title'] ); ?></h3>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/sections/ads' ); ?>
	</div>
</section>
