<?php
/**
 * Page d'accueil : enchaînement des sections, chacune réglable dans « Gestion du site ».
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php
	get_template_part( 'template-parts/sections/hero' );
	get_template_part( 'template-parts/sections/stats' );
	get_template_part( 'template-parts/sections/club' );
	get_template_part( 'template-parts/sections/objectifs' );
	get_template_part( 'template-parts/sections/equipe' );
	get_template_part( 'template-parts/sections/actualites' );
	get_template_part( 'template-parts/sections/partenaires' );
	get_template_part( 'template-parts/sections/contact' );

	// Contenu de la page d'accueil statique éventuelle (Réglages > Lecture), affiché après les sections.
	if ( 'page' === get_option( 'show_on_front' ) ) {
		while ( have_posts() ) {
			the_post();
			if ( '' !== trim( get_the_content() ) ) {
				echo '<section class="section"><div class="container entry-content">';
				the_content();
				echo '</div></section>';
			}
		}
	}
	?>
</main>
<?php
get_footer();
