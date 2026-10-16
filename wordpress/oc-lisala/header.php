<?php
/**
 * En-tête du site (logo, menu principal, menu mobile).
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

$ocl_short = ocl_opt( 'general', 'short_name' );
$ocl_full  = ocl_opt( 'general', 'full_name' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>document.documentElement.classList.remove("no-js");</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#contenu"><?php esc_html_e( 'Aller au contenu', 'oc-lisala' ); ?></a>

<header class="site-header<?php echo is_front_page() ? '' : ' site-header--solid'; ?>" id="site-header">
	<div class="container site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php echo ocl_logo( 'brand__logo', 48, 50, '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans ocl_logo(). ?>
			<span class="brand__text">
				<span class="brand__name"><?php echo esc_html( $ocl_short ? $ocl_short : get_bloginfo( 'name' ) ); ?></span>
				<?php if ( $ocl_full ) : ?>
					<span class="brand__sub"><?php echo esc_html( $ocl_full ); ?></span>
				<?php endif; ?>
				<span class="screen-reader-text"><?php esc_html_e( ', retour à l\'accueil', 'oc-lisala' ); ?></span>
			</span>
		</a>

		<nav class="main-nav" id="main-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'oc-lisala' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'main-nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'ocl_primary_menu_fallback',
				)
			);
			?>
		</nav>

		<button class="nav-toggle" id="nav-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'oc-lisala' ); ?>">
			<span class="nav-toggle__bar"></span>
			<span class="nav-toggle__bar"></span>
			<span class="nav-toggle__bar"></span>
		</button>
	</div>
</header>
