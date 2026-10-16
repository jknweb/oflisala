<?php
/**
 * Articles d'une catégorie.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php ocl_page_hero( single_cat_title( '', false ), __( 'Actualités', 'oc-lisala' ), esc_html( wp_strip_all_tags( category_description() ) ) ); ?>
	<?php get_template_part( 'template-parts/loop-grid' ); ?>
</main>
<?php
get_footer();
