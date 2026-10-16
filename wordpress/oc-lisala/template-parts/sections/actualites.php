<?php
/**
 * Section « Actualités » : derniers articles de la catégorie choisie.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o = ocl_options( 'actualites' );
if ( ! $o['enabled'] ) {
	return;
}

$args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => max( 1, (int) $o['count'] ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( (int) $o['category'] ) {
	$args['cat'] = (int) $o['category'];
}
$news    = new WP_Query( $args );
$all_url = ocl_news_archive_url();
$extra   = ( $all_url && $o['all_label'] && $news->have_posts() ) ? '<a class="link-arrow" href="' . esc_url( $all_url ) . '">' . esc_html( $o['all_label'] ) . '</a>' : '';
?>
<section class="section section--light" id="actualites"<?php echo ocl_labelledby( $o['title'], 'news-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container">
		<?php
		ocl_section_head(
			array(
				'eyebrow' => $o['eyebrow'],
				'title'   => $o['title'],
				'intro'   => $o['intro'],
				'id'      => 'news-title',
				'extra'   => $extra,
			)
		);
		?>

		<?php if ( $news->have_posts() ) : ?>
			<div class="news-grid news-grid--<?php echo (int) $news->post_count; ?>">
				<?php
				while ( $news->have_posts() ) {
					$news->the_post();
					get_template_part(
						'template-parts/content-card',
						null,
						array(
							'featured' => 0 === $news->current_post && $news->post_count > 2,
							'options'  => $o,
						)
					);
				}
				wp_reset_postdata();
				?>
			</div>
		<?php elseif ( $o['empty_text'] ) : ?>
			<p class="empty-state"><?php echo esc_html( $o['empty_text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
