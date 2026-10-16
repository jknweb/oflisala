<?php
/**
 * Article individuel.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="contenu">
	<?php while ( have_posts() ) : ?>
		<?php
		the_post();
		$ocl_cats = get_the_category();
		$ocl_meta = '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
		ocl_page_hero( get_the_title(), $ocl_cats ? $ocl_cats[0]->name : __( 'Actualités', 'oc-lisala' ), $ocl_meta );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section section--article' ); ?>>
			<div class="container container--narrow">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail media">
						<?php the_post_thumbnail( 'ocl-news', array( 'class' => 'photo', 'sizes' => '(max-width: 820px) 100vw, 760px' ) ); ?>
					</figure>
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

				<footer class="entry-footer">
					<?php if ( $ocl_cats ) : ?>
						<p class="entry-tags">
							<?php foreach ( $ocl_cats as $ocl_cat ) : ?>
								<a class="news-card__cat news-card__cat--inline" href="<?php echo esc_url( get_category_link( $ocl_cat ) ); ?>"><?php echo esc_html( $ocl_cat->name ); ?></a>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
					<?php
					the_post_navigation(
						array(
							'prev_text'          => '<span class="post-nav__label">' . esc_html__( 'Article précédent', 'oc-lisala' ) . '</span><span class="post-nav__title">%title</span>',
							'next_text'          => '<span class="post-nav__label">' . esc_html__( 'Article suivant', 'oc-lisala' ) . '</span><span class="post-nav__title">%title</span>',
							'screen_reader_text' => __( 'Navigation entre les articles', 'oc-lisala' ),
						)
					);
					$ocl_all = ocl_news_archive_url();
					?>
					<p class="entry-back">
						<a class="link-arrow link-arrow--back" href="<?php echo esc_url( $ocl_all ? $ocl_all : ocl_home_anchor( 'actualites' ) ); ?>"><?php esc_html_e( 'Toutes les actualités', 'oc-lisala' ); ?></a>
					</p>
				</footer>

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
