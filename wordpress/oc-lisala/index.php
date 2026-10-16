<?php
/**
 * Gabarit de secours : liste des articles (page des articles, archives sans gabarit dédié).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ocl_title = __( 'Actualités', 'oc-lisala' );
if ( is_home() && get_option( 'page_for_posts' ) ) {
	$ocl_title = get_the_title( (int) get_option( 'page_for_posts' ) );
}
?>
<main id="contenu">
	<?php ocl_page_hero( $ocl_title, ocl_opt( 'general', 'short_name' ) ); ?>
	<?php get_template_part( 'template-parts/loop-grid' ); ?>
</main>
<?php
get_footer();
