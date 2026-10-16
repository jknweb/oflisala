<?php
/**
 * Import des photos d'illustration du site d'origine dans la médiathèque.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Photos du site d'origine (Pexels, licence libre) et emplacement de chacune.
 *
 * @return array
 */
function ocl_original_photos() {
	$base = 'https://images.pexels.com/photos/%1$d/pexels-photo-%1$d.jpeg?auto=compress&cs=tinysrgb&fit=crop&w=%2$d&h=%3$d';
	return array(
		array( sprintf( $base, 30782372, 1920, 1200 ), 'hero', 'image', "Joueur de football contrôlant le ballon sur une pelouse" ),
		array( sprintf( $base, 38242318, 900, 1100 ), 'club', 'image_main', 'Footballeur tenant un ballon sur un terrain' ),
		array( sprintf( $base, 38404136, 600, 600 ), 'club', 'image_small', 'Jeune footballeur en maillot rouge tenant un ballon' ),
		array( sprintf( $base, 36826363, 1600, 640 ), 'objectifs', 'banner_images', "Jeune joueur en maillot rouge concentré sur le ballon pendant un entraînement" ),
		array( sprintf( $base, 38404129, 1600, 640 ), 'objectifs', 'banner_images', 'Joueurs pendant un entraînement de football' ),
		array( sprintf( $base, 28735311, 1600, 640 ), 'objectifs', 'banner_images', 'Séance d\'entraînement sur un terrain de football' ),
		array( sprintf( $base, 34927818, 1600, 900 ), 'sections', 'partners_image', 'Supporters dans les tribunes d\'un stade' ),
	);
}

/**
 * Traite le bouton « Importer les photos ».
 */
function ocl_handle_import_photos() {
	if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'upload_files' ) ) {
		wp_die( esc_html__( "Vous n'avez pas le droit d'importer des photos.", 'oc-lisala' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'ocl_import_photos' );

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$imported = 0;
	$skipped  = 0;
	$errors   = array();
	$updates  = array();

	foreach ( ocl_original_photos() as $photo ) {
		list( $url, $page, $key, $alt ) = $photo;
		if ( ! isset( $updates[ $page ] ) ) {
			$updates[ $page ] = get_option( 'ocl_' . $page, array() );
			$updates[ $page ] = is_array( $updates[ $page ] ) ? $updates[ $page ] : array();
		}
		$current  = isset( $updates[ $page ][ $key ] ) ? $updates[ $page ][ $key ] : ( 'banner_images' === $key ? array() : 0 );
		$is_multi = 'banner_images' === $key;
		if ( ( $is_multi && count( (array) $current ) >= 3 ) || ( ! $is_multi && $current ) ) {
			++$skipped;
			continue;
		}

		$id = media_sideload_image( $url, 0, $alt, 'id' );
		if ( is_wp_error( $id ) ) {
			$errors[] = $id->get_error_message();
			continue;
		}
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		++$imported;

		if ( $is_multi ) {
			$current                  = array_map( 'absint', (array) $current );
			$current[]                = $id;
			$updates[ $page ][ $key ] = $current;
		} else {
			$updates[ $page ][ $key ] = $id;
		}
	}

	if ( $imported ) {
		$general = get_option( 'ocl_general', array() );
		$general = is_array( $general ) ? $general : array();
		if ( empty( $general['credits'] ) ) {
			$general['credits']  = 'Photos d\'illustration : <a href="https://www.pexels.com" target="_blank" rel="noopener">Pexels</a>, licence libre. Les personnes photographiées ne sont pas des joueurs du club.';
			$updates['general'] = $general;
		}
	}
	foreach ( $updates as $page => $values ) {
		update_option( 'ocl_' . $page, $values );
	}

	set_transient(
		'ocl_import_result_' . get_current_user_id(),
		array(
			'imported' => $imported,
			'skipped'  => $skipped,
			'errors'   => array_unique( $errors ),
		),
		5 * MINUTE_IN_SECONDS
	);
	wp_safe_redirect( admin_url( 'admin.php?page=' . OCL_ADMIN_MENU ) );
	exit;
}
add_action( 'admin_post_ocl_import_photos', 'ocl_handle_import_photos' );

/**
 * Résultat de l'import, affiché une fois sur la page « Gestion du site ».
 */
function ocl_import_notice() {
	$key    = 'ocl_import_result_' . get_current_user_id();
	$result = get_transient( $key );
	if ( ! $result ) {
		return;
	}
	delete_transient( $key );

	if ( $result['imported'] ) {
		/* translators: %d: nombre de photos. */
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( _n( '%d photo importée et placée sur le site.', '%d photos importées et placées sur le site.', $result['imported'], 'oc-lisala' ), $result['imported'] ) ) . '</p></div>';
	}
	if ( $result['skipped'] && ! $result['imported'] && ! $result['errors'] ) {
		echo '<div class="notice notice-info"><p>' . esc_html__( 'Toutes les images sont déjà renseignées : rien à importer.', 'oc-lisala' ) . '</p></div>';
	}
	if ( $result['errors'] ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( "Certaines photos n'ont pas pu être téléchargées. Votre hébergement doit pouvoir accéder à images.pexels.com.", 'oc-lisala' ) . '</p><ul>';
		foreach ( $result['errors'] as $error ) {
			echo '<li>' . esc_html( $error ) . '</li>';
		}
		echo '</ul></div>';
	}
}
