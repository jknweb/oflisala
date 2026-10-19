<?php
/**
 * Thème OC Lisala : chargement des modules.
 *
 * Organisation :
 *   inc/setup.php            Fonctionnalités WordPress, menus, tailles d'images, menus par défaut
 *   inc/enqueue.php          Polices, styles et scripts
 *   inc/settings-schema.php  Rubriques de « Gestion du site » (champs et valeurs par défaut)
 *   inc/options.php          Lecture et nettoyage des réglages
 *   inc/template-tags.php    Fonctions d'affichage (boutons, images, liens WhatsApp…)
 *   inc/whatsapp-form.php    Formulaire de contact vers WhatsApp (validation serveur)
 *   inc/menus.php            Menus : classes, sous-menus, affichage ordinateur / mobile
 *   inc/seo.php              Description, Open Graph, données structurées
 *   inc/admin/               Pages de réglages, champs, installation de l'extension, import des photos
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

define( 'OCL_THEME_VERSION', '1.0.1' );

$ocl_inc = get_template_directory() . '/inc/';

require_once $ocl_inc . 'icons.php';
require_once $ocl_inc . 'settings-schema.php';
require_once $ocl_inc . 'options.php';
require_once $ocl_inc . 'setup.php';
require_once $ocl_inc . 'enqueue.php';
require_once $ocl_inc . 'template-tags.php';
require_once $ocl_inc . 'whatsapp-form.php';
require_once $ocl_inc . 'menus.php';
require_once $ocl_inc . 'seo.php';
require_once $ocl_inc . 'admin/plugin-installer.php';

if ( is_admin() ) {
	require_once $ocl_inc . 'admin/fields.php';
	require_once $ocl_inc . 'admin/settings-pages.php';
	require_once $ocl_inc . 'admin/import-photos.php';
}

unset( $ocl_inc );
