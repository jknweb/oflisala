<?php
/**
 * Archives (dates, auteurs, étiquettes).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php ocl_page_hero( wp_strip_all_tags( get_the_archive_title() ), __( 'Actualités', 'oc-lisala' ), wp_kses_post( get_the_archive_description() ) ); ?>
	<?php get_template_part( 'template-parts/loop-grid' ); ?>
</main>
<?php
get_footer();
