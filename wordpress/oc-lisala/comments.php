<?php
/**
 * Commentaires (affichés seulement s'ils sont ouverts sur l'article).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			/* translators: %s: nombre de commentaires. */
			echo esc_html( sprintf( _n( '%s commentaire', '%s commentaires', get_comments_number(), 'oc-lisala' ), number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="note"><?php esc_html_e( 'Les commentaires sont fermés.', 'oc-lisala' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit' => 'btn btn--primary',
		)
	);
	?>
</section>
