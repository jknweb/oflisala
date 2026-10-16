<?php
/**
 * Grille d'articles des pages de liste (actualités, catégories, recherche).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$ocl_news_options = ocl_options( 'actualites' );
?>
<section class="section section--light section--list">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="news-list">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part(
						'template-parts/content-card',
						null,
						array(
							'options' => $ocl_news_options,
							'heading' => 'h2',
						)
					);
				}
				?>
			</div>
			<?php ocl_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</section>
