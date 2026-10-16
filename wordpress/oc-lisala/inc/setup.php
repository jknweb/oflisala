<?php
/**
 * Configuration du thème : fonctionnalités WordPress, menus, tailles d'images.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fonctionnalités prises en charge.
 */
function ocl_setup() {
	load_theme_textdomain( 'oc-lisala', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Rouge OC Lisala', 'oc-lisala' ),
				'slug'  => 'rouge',
				'color' => '#A3060F',
			),
			array(
				'name'  => __( 'Noir', 'oc-lisala' ),
				'slug'  => 'noir',
				'color' => '#1C1C1E',
			),
			array(
				'name'  => __( 'Vert terrain', 'oc-lisala' ),
				'slug'  => 'vert',
				'color' => '#2F7A43',
			),
			array(
				'name'  => __( 'Gris clair', 'oc-lisala' ),
				'slug'  => 'gris',
				'color' => '#F4F4F2',
			),
			array(
				'name'  => __( 'Blanc', 'oc-lisala' ),
				'slug'  => 'blanc',
				'color' => '#FFFFFF',
			),
		)
	);
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/editor.css', ocl_fonts_url() ) );

	register_nav_menus(
		array(
			'primary'  => __( 'Menu principal (en-tête)', 'oc-lisala' ),
			'footer-1' => __( 'Pied de page, colonne 1', 'oc-lisala' ),
			'footer-2' => __( 'Pied de page, colonne 2', 'oc-lisala' ),
		)
	);

	add_image_size( 'ocl-card', 600, 750, true );      // Cartes de l'équipe (4:5).
	add_image_size( 'ocl-news', 1200, 750, true );     // Actualités (16:10).
	add_image_size( 'ocl-banner', 1600, 640, true );   // Bannière des objectifs (5:2).
	add_image_size( 'ocl-hero', 2200, 1300, false );   // Grandes bannières.
}
add_action( 'after_setup_theme', 'ocl_setup' );

/**
 * Largeur du contenu des articles (embeds, images).
 */
function ocl_content_width() {
	$GLOBALS['content_width'] = 760; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
add_action( 'after_setup_theme', 'ocl_content_width', 0 );

/**
 * Tailles d'images proposées dans l'éditeur.
 *
 * @param array $sizes Tailles.
 * @return array
 */
function ocl_image_size_names( $sizes ) {
	return array_merge( $sizes, array( 'ocl-news' => __( 'Large 16:10', 'oc-lisala' ) ) );
}
add_filter( 'image_size_names_choose', 'ocl_image_size_names' );

/**
 * Extrait plus court pour les cartes d'actualités.
 *
 * @return int
 */
function ocl_excerpt_length() {
	return 26;
}
add_filter( 'excerpt_length', 'ocl_excerpt_length' );

/**
 * Points de suspension simples à la fin des extraits.
 *
 * @return string
 */
function ocl_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'ocl_excerpt_more' );

/**
 * À l'activation du thème : menus par défaut pointant vers les sections de l'accueil.
 * Rien n'est créé si un menu est déjà placé à l'emplacement.
 */
function ocl_create_default_menus() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$home      = home_url( '/' );
	$menus     = array(
		'primary'  => array(
			__( 'Menu principal', 'oc-lisala' ),
			array(
				__( 'Accueil', 'oc-lisala' )        => $home,
				__( 'Le Club', 'oc-lisala' )        => $home . '#club',
				__( 'Projet sportif', 'oc-lisala' ) => $home . '#projet',
				__( 'Équipe', 'oc-lisala' )         => $home . '#equipe',
				__( 'Actualités', 'oc-lisala' )     => $home . '#actualites',
				__( 'Partenaires', 'oc-lisala' )    => $home . '#partenaires',
				__( 'Contact', 'oc-lisala' )        => $home . '#contact',
			),
		),
		'footer-1' => array(
			__( 'Pied de page : le site', 'oc-lisala' ),
			array(
				__( 'Le Club', 'oc-lisala' )        => $home . '#club',
				__( 'Projet sportif', 'oc-lisala' ) => $home . '#projet',
				__( 'Équipe', 'oc-lisala' )         => $home . '#equipe',
				__( 'Actualités', 'oc-lisala' )     => $home . '#actualites',
			),
		),
		'footer-2' => array(
			__( 'Pied de page : rejoindre', 'oc-lisala' ),
			array(
				__( 'Partenaires', 'oc-lisala' )        => $home . '#partenaires',
				__( 'Devenir partenaire', 'oc-lisala' ) => $home . '?sujet=Partenariat#contact',
				__( 'Contact', 'oc-lisala' )            => $home . '#contact',
			),
		),
	);

	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}
		$existing = wp_get_nav_menu_object( $menu[0] );
		$menu_id  = $existing ? $existing->term_id : wp_create_nav_menu( $menu[0] );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}
		if ( ! $existing ) {
			$position = 1;
			foreach ( $menu[1] as $title => $url ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'    => $title,
						'menu-item-url'      => $url,
						'menu-item-type'     => 'custom',
						'menu-item-status'   => 'publish',
						'menu-item-position' => $position++,
					)
				);
			}
		}
		$locations[ $location ] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'after_switch_theme', 'ocl_create_default_menus' );
