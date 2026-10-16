<?php
/**
 * Aucun contenu trouvé.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="empty-block">
	<p class="empty-state">
		<?php
		if ( is_search() ) {
			esc_html_e( 'Aucun résultat pour cette recherche. Essayez avec d\'autres mots.', 'oc-lisala' );
		} else {
			esc_html_e( 'Aucun article publié pour le moment.', 'oc-lisala' );
		}
		?>
	</p>
	<?php get_search_form(); ?>
</div>
