<?php
/**
 * Feuilles de style et scripts.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Polices du site : Teko (titres, labels) et Inter (texte).
 *
 * @return string
 */
function ocl_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap';
}

/**
 * Chargement côté visiteurs.
 */
function ocl_enqueue_assets() {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'ocl-fonts', ocl_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- version gérée par Google Fonts.
	wp_enqueue_style( 'ocl-main', $uri . '/assets/css/main.css', array( 'ocl-fonts' ), OCL_THEME_VERSION );

	wp_enqueue_script( 'ocl-main', $uri . '/assets/js/main.js', array(), OCL_THEME_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script(
		'ocl-main',
		'oclSite',
		array(
			'adsDelay' => (int) ocl_opt( 'sections', 'ads_delay' ) * 1000,
			'i18n'     => array(
				'required'  => __( 'Ce champ est obligatoire.', 'oc-lisala' ),
				'email'     => __( "Merci d'indiquer une adresse e-mail valide.", 'oc-lisala' ),
				'tel'       => __( "Merci d'indiquer un numéro de téléphone valide.", 'oc-lisala' ),
				/* translators: %d: nombre de caractères. */
				'minlength' => __( "Merci d'écrire au moins %d caractères.", 'oc-lisala' ),
				'openMenu'  => __( 'Ouvrir le menu', 'oc-lisala' ),
				'closeMenu' => __( 'Fermer le menu', 'oc-lisala' ),
				'submenu'   => __( 'Afficher le sous-menu', 'oc-lisala' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ocl_enqueue_assets' );

/**
 * Préconnexion aux serveurs de polices.
 *
 * @param array  $urls          Adresses.
 * @param string $relation_type Type.
 * @return array
 */
function ocl_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ocl_resource_hints', 10, 2 );
