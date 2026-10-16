<?php
/**
 * Menus : classes du design d'origine, sous-menus, affichage ordinateur / mobile par élément.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Classe .main-nav__link sur les liens du menu principal.
 *
 * @param array    $atts Attributs.
 * @param WP_Post  $item Élément.
 * @param stdClass $args Arguments.
 * @return array
 */
function ocl_nav_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$atts['class'] = trim( ( isset( $atts['class'] ) ? $atts['class'] : '' ) . ' main-nav__link' );
		// Page en cours (les ancres de l'accueil sont gérées au défilement par main.js).
		if ( ! is_front_page() && false === strpos( (string) $item->url, '#' ) && array_intersect( array( 'current-menu-item', 'current-menu-parent', 'current_page_parent' ), (array) $item->classes ) ) {
			$atts['class'] .= ' is-active';
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ocl_nav_link_attributes', 10, 3 );

/**
 * Bouton d'ouverture des sous-menus sur mobile.
 *
 * @param string   $output HTML de l'élément.
 * @param WP_Post  $item   Élément.
 * @param int      $depth  Profondeur.
 * @param stdClass $args   Arguments.
 * @return string
 */
function ocl_nav_submenu_toggle( $output, $item, $depth, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$output .= '<button type="button" class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">' . esc_html__( 'Afficher le sous-menu', 'oc-lisala' ) . ' : ' . esc_html( $item->title ) . '</span></button>';
	}
	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'ocl_nav_submenu_toggle', 10, 4 );

/**
 * Cases « Masquer sur ordinateur / sur mobile » dans Apparence > Menus.
 *
 * @param int $item_id Élément de menu.
 */
function ocl_menu_item_fields( $item_id ) {
	$hide = (array) get_post_meta( $item_id, '_ocl_menu_hide', true );
	wp_nonce_field( 'ocl_menu_item_' . $item_id, 'ocl_menu_nonce_' . $item_id );
	?>
	<fieldset class="field-ocl-visibility description description-wide">
		<legend><?php esc_html_e( 'Affichage', 'oc-lisala' ); ?></legend>
		<label><input type="checkbox" name="ocl_menu_hide[<?php echo (int) $item_id; ?>][]" value="desktop" <?php checked( in_array( 'desktop', $hide, true ) ); ?>> <?php esc_html_e( 'Masquer sur ordinateur', 'oc-lisala' ); ?></label>
		&nbsp;
		<label><input type="checkbox" name="ocl_menu_hide[<?php echo (int) $item_id; ?>][]" value="mobile" <?php checked( in_array( 'mobile', $hide, true ) ); ?>> <?php esc_html_e( 'Masquer sur mobile', 'oc-lisala' ); ?></label>
	</fieldset>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'ocl_menu_item_fields' );

/**
 * Enregistre les cases d'affichage.
 *
 * @param int $menu_id Menu.
 * @param int $item_id Élément.
 */
function ocl_save_menu_item_fields( $menu_id, $item_id ) {
	$nonce = 'ocl_menu_nonce_' . $item_id;
	if ( ! isset( $_POST[ $nonce ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ $nonce ] ) ), 'ocl_menu_item_' . $item_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$values = isset( $_POST['ocl_menu_hide'][ $item_id ] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['ocl_menu_hide'][ $item_id ] ) ) : array();
	$values = array_values( array_intersect( $values, array( 'desktop', 'mobile' ) ) );
	if ( $values ) {
		update_post_meta( $item_id, '_ocl_menu_hide', $values );
	} else {
		delete_post_meta( $item_id, '_ocl_menu_hide' );
	}
}
add_action( 'wp_update_nav_menu_item', 'ocl_save_menu_item_fields', 10, 2 );

/**
 * Classes CSS correspondant aux cases d'affichage.
 *
 * @param string[] $classes Classes.
 * @param WP_Post  $item    Élément.
 * @return string[]
 */
function ocl_menu_item_classes( $classes, $item ) {
	foreach ( (array) get_post_meta( $item->ID, '_ocl_menu_hide', true ) as $where ) {
		if ( in_array( $where, array( 'desktop', 'mobile' ), true ) ) {
			$classes[] = 'hide-on-' . $where;
		}
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'ocl_menu_item_classes', 10, 2 );

/**
 * Menu principal de secours tant qu'aucun menu n'est placé : liens vers les sections affichées.
 */
function ocl_primary_menu_fallback() {
	$links = array(
		''            => __( 'Accueil', 'oc-lisala' ),
		'club'        => __( 'Le Club', 'oc-lisala' ),
		'projet'      => __( 'Projet sportif', 'oc-lisala' ),
		'equipe'      => __( 'Équipe', 'oc-lisala' ),
		'actualites'  => __( 'Actualités', 'oc-lisala' ),
		'partenaires' => __( 'Partenaires', 'oc-lisala' ),
		'contact'     => __( 'Contact', 'oc-lisala' ),
	);
	echo '<ul class="main-nav__list">';
	foreach ( $links as $anchor => $label ) {
		$url = '' === $anchor ? home_url( '/' ) : home_url( '/#' . $anchor );
		printf( '<li class="menu-item"><a class="main-nav__link" href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}
