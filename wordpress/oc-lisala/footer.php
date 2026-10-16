<?php
/**
 * Pied de page, bouton WhatsApp flottant, retour en haut.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$ocl_g      = ocl_options( 'general' );
$ocl_wa     = ocl_whatsapp_url( ocl_opt( 'contact', 'wa_message' ) );
$ocl_menu_1 = has_nav_menu( 'footer-1' );
$ocl_menu_2 = has_nav_menu( 'footer-2' );
?>
<footer class="site-footer">
	<div class="site-footer__stripe" aria-hidden="true"></div>
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php echo ocl_logo( '', 84, 88, sprintf( /* translators: %s: nom du club. */ __( 'Logo de %s', 'oc-lisala' ), $ocl_g['short_name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans ocl_logo(). ?>
			<?php if ( $ocl_g['full_name'] ) : ?>
				<p class="site-footer__name"><?php echo esc_html( $ocl_g['full_name'] ); ?></p>
			<?php endif; ?>
			<?php if ( $ocl_g['motto'] ) : ?>
				<p class="site-footer__motto"><?php echo esc_html( $ocl_g['motto'] ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $ocl_menu_1 ) : ?>
			<nav class="site-footer__col" aria-label="<?php echo esc_attr( $ocl_g['footer_title_1'] ? $ocl_g['footer_title_1'] : __( 'Plan du site', 'oc-lisala' ) ); ?>">
				<?php if ( $ocl_g['footer_title_1'] ) : ?>
					<h2 class="site-footer__title"><?php echo esc_html( $ocl_g['footer_title_1'] ); ?></h2>
				<?php endif; ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-1', 'container' => false, 'depth' => 1 ) ); ?>
			</nav>
		<?php endif; ?>

		<?php if ( $ocl_menu_2 ) : ?>
			<nav class="site-footer__col" aria-label="<?php echo esc_attr( $ocl_g['footer_title_2'] ? $ocl_g['footer_title_2'] : __( 'Liens', 'oc-lisala' ) ); ?>">
				<?php if ( $ocl_g['footer_title_2'] ) : ?>
					<h2 class="site-footer__title"><?php echo esc_html( $ocl_g['footer_title_2'] ); ?></h2>
				<?php endif; ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-2', 'container' => false, 'depth' => 1 ) ); ?>
			</nav>
		<?php endif; ?>

		<?php if ( $ocl_g['address'] || $ocl_g['phone'] || $ocl_g['email'] ) : ?>
			<div class="site-footer__col">
				<?php if ( $ocl_g['footer_title_3'] ) : ?>
					<h2 class="site-footer__title"><?php echo esc_html( $ocl_g['footer_title_3'] ); ?></h2>
				<?php endif; ?>
				<ul>
					<?php if ( $ocl_g['address'] ) : ?>
						<li><?php echo nl2br( esc_html( $ocl_g['address'] ) ); ?></li>
					<?php endif; ?>
					<?php if ( $ocl_g['phone'] ) : ?>
						<li><?php echo ocl_phone_link( $ocl_g['phone'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans ocl_phone_link(). ?></li>
					<?php endif; ?>
					<?php if ( $ocl_g['email'] ) : ?>
						<li><a href="mailto:<?php echo esc_attr( antispambot( $ocl_g['email'] ) ); ?>"><?php echo esc_html( antispambot( $ocl_g['email'] ) ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<div class="container site-footer__bottom">
		<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( $ocl_g['copyright'] ); ?></p>
		<?php if ( $ocl_g['credits'] ) : ?>
			<p class="site-footer__credits"><?php echo wp_kses( $ocl_g['credits'], array( 'a' => array( 'href' => true, 'target' => true, 'rel' => true ), 'strong' => array(), 'em' => array(), 'br' => array() ) ); ?></p>
		<?php endif; ?>
	</div>
</footer>

<?php if ( $ocl_wa && ocl_opt( 'contact', 'float' ) ) : ?>
	<a class="whatsapp-float" href="<?php echo esc_url( $ocl_wa ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: nom du club. */ __( 'Contacter %s sur WhatsApp', 'oc-lisala' ), $ocl_g['short_name'] ) ); ?>">
		<?php echo ocl_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statique. ?>
	</a>
<?php endif; ?>

<button class="to-top" type="button" data-action="to-top" aria-label="<?php esc_attr_e( 'Revenir en haut de la page', 'oc-lisala' ); ?>">
	<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 15l6-6 6 6"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
