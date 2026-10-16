<?php
/**
 * Formulaire de recherche.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$ocl_search_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $ocl_search_id ); ?>"><?php esc_html_e( 'Rechercher :', 'oc-lisala' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $ocl_search_id ); ?>" class="search-form__input" placeholder="<?php esc_attr_e( 'Rechercher une actualité…', 'oc-lisala' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="btn btn--dark"><?php esc_html_e( 'Rechercher', 'oc-lisala' ); ?></button>
</form>
