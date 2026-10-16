<?php
/**
 * Bannière principale.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o     = ocl_options( 'hero' );
$image = ocl_image(
	$o['image'],
	'ocl-hero',
	array(
		'sizes'         => '100vw',
		'fetchpriority' => 'high',
		'loading'       => 'eager',
		'style'         => sprintf( 'object-position:%d%% %d%%', (int) $o['focal_x'], (int) $o['focal_y'] ),
	)
);
$title = '' !== $o['title_1'] || '' !== $o['title_2'] || '' !== $o['badge'];
?>
<section class="hero<?php echo $o['zoom'] ? '' : ' hero--static'; ?>" id="accueil"<?php echo $title ? ' aria-labelledby="hero-title"' : ''; ?>>
	<div class="hero__media media<?php echo $image ? '' : ' is-missing'; ?>">
		<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?>
	</div>
	<div class="hero__overlay" aria-hidden="true"></div>
	<svg class="hero__pitch" viewBox="0 0 600 600" aria-hidden="true" focusable="false">
		<rect x="40" y="40" width="520" height="520" />
		<line x1="40" y1="300" x2="560" y2="300" />
		<circle cx="300" cy="300" r="90" />
		<circle cx="300" cy="300" r="4" class="dot" />
		<rect x="190" y="40" width="220" height="90" />
		<rect x="190" y="470" width="220" height="90" />
	</svg>
	<div class="hero__stripes" aria-hidden="true"></div>

	<div class="container hero__content">
		<?php if ( '' !== $o['eyebrow'] ) : ?>
			<p class="eyebrow eyebrow--light"><?php echo esc_html( $o['eyebrow'] ); ?></p>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h1 class="hero__title" id="hero-title">
				<?php if ( '' !== $o['badge'] ) : ?>
					<span class="hero__club"><?php echo esc_html( $o['badge'] ); ?></span>
				<?php endif; ?>
				<?php if ( '' !== $o['title_1'] ) : ?>
					<span class="hero__line"><?php echo esc_html( $o['title_1'] ); ?></span>
				<?php endif; ?>
				<?php if ( '' !== $o['title_2'] ) : ?>
					<span class="hero__line hero__line--accent"><?php echo esc_html( $o['title_2'] ); ?></span>
				<?php endif; ?>
			</h1>
		<?php endif; ?>
		<?php if ( '' !== $o['text'] ) : ?>
			<p class="hero__lead"><?php echo esc_html( $o['text'] ); ?></p>
		<?php endif; ?>
		<?php
		$buttons = ocl_button( $o['btn1_label'], $o['btn1'], 'btn btn--primary' ) . ocl_button( $o['btn2_label'], $o['btn2'], 'btn btn--outline-light' );
		if ( $buttons ) {
			echo '<div class="hero__actions">' . $buttons . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans ocl_button().
		}
		?>
	</div>

	<a class="hero__scroll" href="#apres-banniere" aria-label="<?php esc_attr_e( 'Descendre vers la suite de la page', 'oc-lisala' ); ?>"><span></span></a>
</section>
<span id="apres-banniere" class="anchor-target" aria-hidden="true"></span>
