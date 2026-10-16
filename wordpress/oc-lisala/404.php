<?php
/**
 * Page introuvable.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php ocl_page_hero( __( 'Page introuvable', 'oc-lisala' ), __( 'Erreur 404', 'oc-lisala' ) ); ?>
	<section class="section">
		<div class="container container--narrow entry-content">
			<p class="lead"><?php esc_html_e( "Cette page n'existe pas ou a été déplacée.", 'oc-lisala' ); ?></p>
			<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( "Retour à l'accueil", 'oc-lisala' ); ?></a></p>
			<?php get_search_form(); ?>
		</div>
	</section>
</main>
<?php
get_footer();
