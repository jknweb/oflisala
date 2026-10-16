<?php
/**
 * SEO de base : description, Open Graph, données structurées, icônes.
 * Si une extension SEO (Yoast, Rank Math, SEOPress…) est active, elle prend le relais.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Une extension SEO gère-t-elle déjà ces balises ?
 *
 * @return bool
 */
function ocl_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

/**
 * Description de la page en cours.
 *
 * @return string
 */
function ocl_meta_description() {
	if ( is_singular() && ! is_front_page() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '…' );
		if ( $text ) {
			return $text;
		}
	}
	if ( is_category() && category_description() ) {
		return wp_strip_all_tags( category_description() );
	}
	return (string) ocl_opt( 'general', 'meta_description' );
}

/**
 * Balises meta dans l'en-tête.
 */
function ocl_head_meta() {
	echo '<meta name="theme-color" content="#A3060F">' . "\n";

	if ( ! has_site_icon() ) {
		$img = get_template_directory_uri() . '/assets/images/';
		printf( '<link rel="icon" type="image/png" sizes="32x32" href="%s">' . "\n", esc_url( $img . 'favicon-32.png' ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $img . 'apple-touch-icon.png' ) );
	}

	if ( ocl_seo_plugin_active() ) {
		return;
	}

	$description = ocl_meta_description();
	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_trim_words( $description, 40, '…' ) ) );
	}

	$title = wp_get_document_title();
	$image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
	if ( ! $image && ocl_opt( 'hero', 'image' ) ) {
		$image = wp_get_attachment_image_url( (int) ocl_opt( 'hero', 'image' ), 'large' );
	}
	printf( '<meta property="og:type" content="%s">' . "\n", is_single() ? 'article' : 'website' );
	echo '<meta property="og:locale" content="fr_FR">' . "\n";
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $description ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( wp_trim_words( $description, 40, '…' ) ) );
	}
	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}

	if ( is_front_page() ) {
		$data = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'SportsOrganization',
			'name'          => ocl_opt( 'general', 'full_name' ),
			'alternateName' => ocl_opt( 'general', 'short_name' ),
			'url'           => home_url( '/' ),
			'logo'          => get_template_directory_uri() . '/assets/images/apple-touch-icon.png',
			'address'       => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => ocl_opt( 'general', 'address' ),
				'addressLocality' => 'Lisala',
				'addressRegion'   => 'Mongala',
				'addressCountry'  => 'CD',
			),
		);
		$same = array_values( array_filter( array( ocl_opt( 'general', 'facebook' ), ocl_opt( 'general', 'instagram' ), ocl_opt( 'general', 'youtube' ), ocl_opt( 'general', 'tiktok' ) ) ) );
		if ( $same ) {
			$data['sameAs'] = $same;
		}
		if ( ocl_opt( 'general', 'phone' ) ) {
			$data['telephone'] = ocl_opt( 'general', 'phone' );
		}
		if ( ocl_opt( 'general', 'email' ) ) {
			$data['email'] = ocl_opt( 'general', 'email' );
		}
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . "</script>\n";
	}
}
add_action( 'wp_head', 'ocl_head_meta', 2 );
