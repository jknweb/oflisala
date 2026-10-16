<?php
/**
 * Pictogrammes du site (tracés SVG repris du site d'origine).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bibliothèque de pictogrammes : clé => [libellé, contenu SVG 24×24].
 *
 * @return array<string,array{0:string,1:string}>
 */
function ocl_icons() {
	return array(
		'trophee'    => array( __( 'Trophée', 'oc-lisala' ), '<path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0V4ZM7 6H4a3 3 0 0 0 3 4M17 6h3a3 3 0 0 1-3 4"/>' ),
		'pouls'      => array( __( 'Effort physique', 'oc-lisala' ), '<path d="M3 12h4l3-7 4 14 3-7h4"/>' ),
		'etoile'     => array( __( 'Étoile', 'oc-lisala' ), '<path d="M12 2l3 6.5 7 .9-5.2 4.8 1.4 7L12 17.8 5.8 21.2l1.4-7L2 9.4l7-.9z"/>' ),
		'groupe'     => array( __( 'Groupe', 'oc-lisala' ), '<circle cx="9" cy="7" r="3"/><path d="M3 21v-1a6 6 0 0 1 12 0v1M16 3.5a3 3 0 0 1 0 7M21 21v-1a6 6 0 0 0-4-5.6"/>' ),
		'coeur'      => array( __( 'Cœur', 'oc-lisala' ), '<path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10Z"/>' ),
		'progres'    => array( __( 'Progression', 'oc-lisala' ), '<path d="M4 19V5M4 19h16M8 15l4-4 3 3 5-6"/>' ),
		'evenement'  => array( __( 'Manifestation', 'oc-lisala' ), '<path d="M4 4h16v12H4zM8 20h8M12 16v4M8 8h8M8 12h5"/>' ),
		'football'   => array( __( 'Football', 'oc-lisala' ), '<circle cx="12" cy="12" r="9"/><path d="M12 7l4 3-1.5 5h-5L8 10z"/>' ),
		'basketball' => array( __( 'Basketball', 'oc-lisala' ), '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3v18M5.6 5.6c3.2 3.3 3.2 9.5 0 12.8M18.4 5.6c-3.2 3.3-3.2 9.5 0 12.8"/>' ),
		'bouclier'   => array( __( 'Bouclier', 'oc-lisala' ), '<path d="M12 3l8 3v6c0 4.5-3.4 8.2-8 9-4.6-.8-8-4.5-8-9V6z"/>' ),
		'cible'      => array( __( 'Cible', 'oc-lisala' ), '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>' ),
		'calendrier' => array( __( 'Calendrier', 'oc-lisala' ), '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>' ),
	);
}

/**
 * Libellés des pictogrammes (pour les listes déroulantes de l'administration).
 *
 * @return array<string,string>
 */
function ocl_icon_choices() {
	$choices = array();
	foreach ( ocl_icons() as $key => $icon ) {
		$choices[ $key ] = $icon[0];
	}
	return $choices;
}

/**
 * Pictogramme SVG décoratif.
 *
 * @param string $key   Clé.
 * @param string $class Classe CSS.
 * @return string
 */
function ocl_icon( $key, $class ) {
	$icons = ocl_icons();
	if ( ! isset( $icons[ $key ] ) ) {
		return '';
	}
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $icons[ $key ][1] . '</svg>';
}
