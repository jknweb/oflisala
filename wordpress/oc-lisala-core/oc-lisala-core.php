<?php
/**
 * Plugin Name:       OC Lisala – Contenus du club
 * Plugin URI:        https://github.com/jknweb/oflisala
 * Description:       Types de contenus de l'Olympique Club de Lisala : membres de l'équipe, publicités et partenaires officiels. Les contenus restent enregistrés même si le thème change.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Olympique Club de Lisala
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       oc-lisala-core
 *
 * @package OCLisalaCore
 */

defined( 'ABSPATH' ) || exit;

define( 'OCL_CORE_VERSION', '1.0.0' );
define( 'OCL_CORE_FILE', __FILE__ );
define( 'OCL_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'OCL_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once OCL_CORE_DIR . 'includes/post-types.php';
require_once OCL_CORE_DIR . 'includes/meta-boxes.php';
require_once OCL_CORE_DIR . 'includes/admin.php';

/**
 * Activation : enregistre les types de contenus puis régénère les permaliens.
 */
function ocl_core_activate() {
	ocl_core_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ocl_core_activate' );

function ocl_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ocl_core_deactivate' );
