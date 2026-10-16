<?php
/**
 * Section « Objectifs du club » (projet sportif) : cartes et bannière photo en fondu.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'objectifs' );
if ( ! $o['enabled'] ) {
	return;
}
$items  = array_values( (array) $o['items'] );
$count  = count( $items );
$slides = array_slice( array_filter( array_map( 'absint', (array) $o['banner_images'] ) ), 0, 3 );
$bg     = ocl_image( $o['bg_image'], 'ocl-hero', array( 'loading' => 'lazy', 'sizes' => '100vw', 'alt' => '' ) );
?>
<section class="section section--dark section--projet<?php echo $bg ? ' has-bg' : ''; ?>" id="projet"<?php echo ocl_labelledby( $o['title'], 'projet-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $bg ) : ?>
		<div class="section__bg media" aria-hidden="true"><?php echo $bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
	<div class="container">
		<?php
		ocl_section_head(
			array(
				'eyebrow' => $o['eyebrow'],
				'title'   => $o['title'],
				'intro'   => $o['intro'],
				'id'      => 'projet-title',
				'light'   => true,
			)
		);
		?>

		<?php if ( $count ) : ?>
			<div class="axes axes--objectifs<?php echo 0 === ( $count + 1 ) % 4 ? ' axes--span-first' : ''; ?>">
				<?php foreach ( $items as $i => $item ) : ?>
					<article class="axis-card reveal">
						<span class="axis-card__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<?php echo ocl_icon( $item['icon'], 'axis-card__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statique. ?>
						<?php if ( '' !== $item['title'] ) : ?>
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $item['text'] ) : ?>
							<p><?php echo esc_html( $item['text'] ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $slides ) : ?>
			<figure class="projet-banner media media--slides reveal slides-<?php echo count( $slides ); ?>">
				<?php
				foreach ( $slides as $i => $slide ) {
					$attr = array(
						'class'   => 'banner-slide',
						'loading' => 'lazy',
						'sizes'   => '(max-width: 1240px) 100vw, 1180px',
					);
					if ( $i > 0 ) {
						$attr['alt']         = '';
						$attr['aria-hidden'] = 'true';
					}
					echo ocl_image( $slide, 'ocl-banner', $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				$button = ocl_button( $o['banner_btn_label'], $o['banner_btn'], 'btn btn--light' );
				?>
				<?php if ( '' !== $o['banner_line_1'] || '' !== $o['banner_line_2'] || $button ) : ?>
					<figcaption class="projet-banner__caption">
						<?php if ( '' !== $o['banner_line_1'] ) : ?>
							<span><?php echo esc_html( $o['banner_line_1'] ); ?></span>
						<?php endif; ?>
						<?php if ( '' !== $o['banner_line_2'] ) : ?>
							<strong><?php echo esc_html( $o['banner_line_2'] ); ?></strong>
						<?php endif; ?>
						<?php echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>
	</div>
</section>
