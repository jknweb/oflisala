<?php
/**
 * Résultats de recherche.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php
	/* translators: %s: mots recherchés. */
	ocl_page_hero( sprintf( __( 'Recherche : %s', 'oc-lisala' ), get_search_query() ), __( 'Recherche', 'oc-lisala' ) );
	?>
	<?php get_template_part( 'template-parts/loop-grid' ); ?>
</main>
<?php
get_footer();
