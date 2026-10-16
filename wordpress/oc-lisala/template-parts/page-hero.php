<?php
/**
 * Bandeau de titre des pages intérieures (reprend l'habillage de la bannière d'accueil).
 *
 * Arguments : title, eyebrow, meta (HTML déjà échappé).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args,
	array(
		'title'   => '',
		'eyebrow' => '',
		'meta'    => '',
	)
);
?>
<header class="page-hero">
	<svg class="hero__pitch" viewBox="0 0 600 600" aria-hidden="true" focusable="false">
		<rect x="40" y="40" width="520" height="520" />
		<line x1="40" y1="300" x2="560" y2="300" />
		<circle cx="300" cy="300" r="90" />
		<rect x="190" y="40" width="220" height="90" />
		<rect x="190" y="470" width="220" height="90" />
	</svg>
	<div class="hero__stripes" aria-hidden="true"></div>
	<div class="container page-hero__inner">
		<?php if ( $args['eyebrow'] ) : ?>
			<p class="eyebrow eyebrow--light"><?php echo esc_html( $args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
		<?php if ( $args['meta'] ) : ?>
			<p class="page-hero__meta"><?php echo $args['meta']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé par l'appelant. ?></p>
		<?php endif; ?>
	</div>
</header>
