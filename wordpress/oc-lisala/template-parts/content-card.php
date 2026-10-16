<?php
/**
 * Carte d'article (accueil, archives, recherche).
 *
 * Arguments : featured (bool, grande carte), options (réglages de la section Actualités).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$o        = isset( $args['options'] ) ? $args['options'] : ocl_options( 'actualites' );
$featured = ! empty( $args['featured'] );
$heading  = isset( $args['heading'] ) && 'h2' === $args['heading'] ? 'h2' : 'h3';
$cats     = get_the_category();
$cat      = $cats ? $cats[0]->name : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-card reveal' . ( $featured ? ' news-card--featured' : '' ) ); ?>>
	<?php if ( $o['show_image'] ) : ?>
		<a class="news-card__media media<?php echo has_post_thumbnail() ? '' : ' is-missing'; ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail(
					'ocl-news',
					array(
						'class'   => 'photo',
						'alt'     => '',
						'loading' => 'lazy',
						'sizes'   => $featured ? '(max-width: 960px) 100vw, 560px' : '(max-width: 640px) 100vw, 380px',
					)
				);
			}
			?>
			<?php if ( $o['show_category'] && $cat ) : ?>
				<span class="news-card__cat"><?php echo esc_html( $cat ); ?></span>
			<?php endif; ?>
		</a>
	<?php endif; ?>
	<div class="news-card__body">
		<?php if ( $o['show_date'] || ( ! $o['show_image'] && $o['show_category'] && $cat ) ) : ?>
			<span class="news-card__meta">
				<?php if ( $o['show_date'] ) : ?>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php endif; ?>
				<?php if ( ! $o['show_image'] && $o['show_category'] && $cat ) : ?>
					<?php echo $o['show_date'] ? ' · ' : ''; ?><?php echo esc_html( $cat ); ?>
				<?php endif; ?>
			</span>
		<?php endif; ?>
		<<?php echo esc_attr( $heading ); ?> class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_attr( $heading ); ?>>
		<?php if ( $o['show_excerpt'] && ( has_excerpt() || '' !== trim( get_the_content() ) ) ) : ?>
			<p class="news-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php endif; ?>
		<?php if ( $o['read_more'] ) : ?>
			<a class="link-arrow" href="<?php the_permalink(); ?>"><?php echo esc_html( $o['read_more'] ); ?><span class="screen-reader-text"> : <?php the_title(); ?></span></a>
		<?php endif; ?>
	</div>
</article>
