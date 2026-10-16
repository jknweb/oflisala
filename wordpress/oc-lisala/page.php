<?php
/**
 * Page simple.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php ocl_page_hero( get_the_title(), ocl_opt( 'general', 'short_name' ) ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
			<div class="container container--narrow">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail media"><?php the_post_thumbnail( 'large', array( 'class' => 'photo' ) ); ?></figure>
				<?php endif; ?>
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Pages :', 'oc-lisala' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
