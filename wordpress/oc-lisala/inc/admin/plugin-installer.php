<?php
/**
 * Installation en un clic de l'extension « OC Lisala – Contenus du club », fournie avec le thème.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

const OCL_CORE_PLUGIN = 'oc-lisala-core/oc-lisala-core.php';

/**
 * L'extension est-elle active ?
 *
 * @return bool
 */
function ocl_core_active() {
	return defined( 'OCL_CORE_VERSION' ) && post_type_exists( 'ocl_membre' );
}

/**
 * Archive de l'extension livrée dans le thème.
 *
 * @return string
 */
function ocl_core_package() {
	return get_template_directory() . '/inc/plugins/oc-lisala-core.zip';
}

/**
 * Avis dans l'administration tant que l'extension n'est pas active.
 */
function ocl_core_notice() {
	if ( ocl_core_active() || ! current_user_can( 'install_plugins' ) && ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && in_array( $screen->id, array( 'update', 'plugin-install', 'admin_page_ocl-install-core' ), true ) ) {
		return;
	}
	$installed = file_exists( WP_PLUGIN_DIR . '/' . OCL_CORE_PLUGIN );
	$label     = $installed ? __( "Activer l'extension", 'oc-lisala' ) : __( "Installer et activer l'extension", 'oc-lisala' );
	?>
	<div class="notice notice-warning">
		<p><strong><?php esc_html_e( 'Thème OC Lisala :', 'oc-lisala' ); ?></strong>
		<?php esc_html_e( "l'extension « OC Lisala – Contenus du club » est nécessaire pour gérer l'équipe, les publicités et les partenaires. Elle est fournie avec le thème.", 'oc-lisala' ); ?></p>
		<p><a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=ocl-install-core' ), 'ocl_install_core' ) ); ?>"><?php echo esc_html( $label ); ?></a></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ocl_core_notice' );

/**
 * Page cachée qui installe (depuis l'archive du thème) puis active l'extension.
 */
function ocl_install_core_page() {
	$hook = add_submenu_page( OCL_ADMIN_MENU, __( "Installation de l'extension OC Lisala", 'oc-lisala' ), __( "Installation de l'extension OC Lisala", 'oc-lisala' ), 'activate_plugins', 'ocl-install-core', 'ocl_install_core_render' );
	if ( $hook ) {
		add_action( 'load-' . $hook, 'ocl_install_core_run' );
	}
}
add_action( 'admin_menu', 'ocl_install_core_page', 20 );

/**
 * La page d'installation reste accessible par son lien mais n'apparaît pas dans le menu.
 */
function ocl_install_core_page_hide() {
	remove_submenu_page( OCL_ADMIN_MENU, 'ocl-install-core' );
}
add_action( 'admin_head', 'ocl_install_core_page_hide' );

/**
 * Installe puis active l'extension avant l'affichage de la page ; en cas de succès,
 * retour à « Gestion du site ». En cas d'échec, le message est affiché par ocl_install_core_render().
 */
function ocl_install_core_run() {
	check_admin_referer( 'ocl_install_core' );
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$error = '';

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . OCL_CORE_PLUGIN ) ) {
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( esc_html__( "Vous n'avez pas le droit d'installer des extensions.", 'oc-lisala' ), '', array( 'response' => 403 ) );
		}
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$skin     = new Automatic_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );
		$result   = $upgrader->install( ocl_core_package() );
		if ( true !== $result ) {
			$error = is_wp_error( $result ) ? $result->get_error_message() : implode( ' ', array_map( 'wp_strip_all_tags', (array) $skin->get_upgrade_messages() ) );
			if ( '' === $error ) {
				$error = __( "WordPress n'a pas pu écrire dans le dossier des extensions.", 'oc-lisala' );
			}
		}
	}

	if ( '' === $error ) {
		$activated = activate_plugin( OCL_CORE_PLUGIN );
		if ( is_wp_error( $activated ) ) {
			$error = $activated->get_error_message();
		}
	}

	if ( '' === $error ) {
		wp_safe_redirect( admin_url( 'admin.php?page=' . OCL_ADMIN_MENU . '&ocl-core=active' ) );
		exit;
	}
	$GLOBALS['ocl_install_error'] = $error;
}

/**
 * Message d'échec de l'installation.
 */
function ocl_install_core_render() {
	$error = isset( $GLOBALS['ocl_install_error'] ) ? $GLOBALS['ocl_install_error'] : '';
	echo '<div class="wrap"><h1>' . esc_html__( "Installation de l'extension OC Lisala", 'oc-lisala' ) . '</h1>';
	echo '<div class="notice notice-error inline"><p>' . esc_html__( "L'installation a échoué. Vous pouvez téléverser le fichier oc-lisala-core.zip (fourni avec le thème) dans Extensions > Ajouter > Téléverser une extension.", 'oc-lisala' ) . '</p>';
	if ( $error ) {
		echo '<p>' . esc_html( $error ) . '</p>';
	}
	echo '</div></div>';
}

/**
 * Confirmation après l'activation.
 */
function ocl_install_core_done_notice() {
	if ( isset( $_GET['ocl-core'] ) && 'active' === $_GET['ocl-core'] && ocl_core_active() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple message.
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( "Extension « OC Lisala – Contenus du club » installée et activée : l'équipe, les publicités et les partenaires sont maintenant disponibles.", 'oc-lisala' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'ocl_install_core_done_notice' );
