<?php
/**
 * Administration : menu « Gestion du site », colonnes, statut actif, ordre par glisser-déposer.
 *
 * @package OCLisalaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Crée le menu « Gestion du site » s'il n'existe pas encore.
 * Le thème OC Lisala utilise le même identifiant : le premier qui le crée le partage avec l'autre.
 */
function ocl_core_admin_menu() {
	global $admin_page_hooks;
	if ( empty( $admin_page_hooks[ OCL_ADMIN_MENU ] ) ) {
		add_menu_page(
			__( 'Gestion du site', 'oc-lisala-core' ),
			__( 'Gestion du site', 'oc-lisala-core' ),
			'edit_posts',
			OCL_ADMIN_MENU,
			'ocl_core_render_overview',
			'dashicons-shield',
			3
		);
	}
}
add_action( 'admin_menu', 'ocl_core_admin_menu', 5 );

/**
 * Page d'accueil du menu : liste des rubriques disponibles.
 */
function ocl_core_render_overview() {
	global $submenu;
	echo '<div class="wrap"><h1>' . esc_html__( 'Gestion du site', 'oc-lisala-core' ) . '</h1>';
	echo '<p>' . esc_html__( 'Choisissez la rubrique à modifier.', 'oc-lisala-core' ) . '</p><ul class="ocl-overview">';
	foreach ( isset( $submenu[ OCL_ADMIN_MENU ] ) ? $submenu[ OCL_ADMIN_MENU ] : array() as $item ) {
		if ( OCL_ADMIN_MENU === $item[2] || ! current_user_can( $item[1] ) ) {
			continue;
		}
		$url = false !== strpos( $item[2], '.php' ) ? admin_url( $item[2] ) : admin_url( 'admin.php?page=' . $item[2] );
		printf( '<li><a class="button button-hero" href="%s">%s</a></li>', esc_url( $url ), esc_html( wp_strip_all_tags( $item[0] ) ) );
	}
	echo '</ul></div>';
}

/**
 * Types gérés par l'extension, utilisés par les écrans de liste.
 *
 * @return string[]
 */
function ocl_core_type_names() {
	return array_keys( ocl_core_types() );
}

/**
 * Écran de liste : tri par ordre d'affichage et tous les contenus sur une page (pour le glisser-déposer).
 *
 * @param WP_Query $query Requête.
 */
function ocl_core_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	global $pagenow;
	$type = $query->get( 'post_type' );
	if ( 'edit.php' !== $pagenow || ! in_array( $type, ocl_core_type_names(), true ) ) {
		return;
	}
	if ( ! $query->get( 'orderby' ) ) {
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			)
		);
	}
	$query->set( 'posts_per_page', 300 );
}
add_action( 'pre_get_posts', 'ocl_core_admin_order' );

/**
 * Colonnes de la liste.
 *
 * @param array $columns Colonnes.
 * @return array
 */
function ocl_core_columns( $columns ) {
	$new = array(
		'cb'         => $columns['cb'],
		'ocl_handle' => '<span class="screen-reader-text">' . esc_html__( 'Déplacer', 'oc-lisala-core' ) . '</span>',
		'ocl_image'  => esc_html__( 'Image', 'oc-lisala-core' ),
		'title'      => $columns['title'],
		'ocl_info'   => esc_html__( 'Détails', 'oc-lisala-core' ),
		'ocl_order'  => esc_html__( 'Ordre', 'oc-lisala-core' ),
		'ocl_active' => esc_html__( 'Statut', 'oc-lisala-core' ),
	);
	return $new;
}

/**
 * Contenu des colonnes.
 *
 * @param string $column  Colonne.
 * @param int    $post_id Contenu.
 */
function ocl_core_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'ocl_handle':
			echo '<span class="ocl-handle dashicons dashicons-menu" title="' . esc_attr__( 'Glisser pour réordonner', 'oc-lisala-core' ) . '"></span>';
			break;
		case 'ocl_image':
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, array( 64, 64 ), array( 'class' => 'ocl-thumb' ) );
			} else {
				echo '<span class="ocl-thumb ocl-thumb--empty" aria-hidden="true"></span>';
			}
			break;
		case 'ocl_info':
			$type = get_post_type( $post_id );
			if ( 'ocl_membre' === $type ) {
				$groups = ocl_core_member_groups();
				$group  = ocl_core_field( $post_id, 'group' );
				echo esc_html( ocl_core_field( $post_id, 'role' ) );
				echo '<br><span class="description">' . esc_html( isset( $groups[ $group ] ) ? $groups[ $group ] : '' );
				$number = ocl_core_field( $post_id, 'number' );
				if ( '' !== $number ) {
					echo ' · N° ' . esc_html( $number );
				}
				echo '</span>';
			} else {
				$url = ocl_core_field( $post_id, 'url' );
				echo $url ? '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $url ) . '</a>' : '<span class="description">' . esc_html__( 'Sans lien', 'oc-lisala-core' ) . '</span>';
			}
			break;
		case 'ocl_order':
			echo '<span class="ocl-order-value">' . (int) get_post_field( 'menu_order', $post_id ) . '</span>';
			break;
		case 'ocl_active':
			$active = ocl_core_is_active( $post_id );
			if ( current_user_can( 'edit_post', $post_id ) ) {
				$url = wp_nonce_url(
					admin_url( 'admin-post.php?action=ocl_toggle_active&post=' . $post_id ),
					'ocl_toggle_' . $post_id
				);
				printf(
					'<a class="ocl-status %1$s" href="%2$s" title="%3$s">%4$s</a>',
					$active ? 'is-active' : 'is-inactive',
					esc_url( $url ),
					esc_attr( $active ? __( 'Cliquer pour désactiver', 'oc-lisala-core' ) : __( 'Cliquer pour activer', 'oc-lisala-core' ) ),
					esc_html( $active ? __( 'Actif', 'oc-lisala-core' ) : __( 'Inactif', 'oc-lisala-core' ) )
				);
			} else {
				echo esc_html( $active ? __( 'Actif', 'oc-lisala-core' ) : __( 'Inactif', 'oc-lisala-core' ) );
			}
			break;
	}
}

foreach ( array( 'ocl_membre', 'ocl_publicite', 'ocl_partenaire' ) as $ocl_type ) {
	add_filter( "manage_{$ocl_type}_posts_columns", 'ocl_core_columns' );
	add_action( "manage_{$ocl_type}_posts_custom_column", 'ocl_core_column_content', 10, 2 );
}
unset( $ocl_type );

/**
 * Active ou désactive un contenu depuis la liste.
 */
function ocl_core_toggle_active() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( ! $post_id || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'ocl_toggle_' . $post_id ) ) {
		wp_die( esc_html__( 'Lien expiré. Rechargez la page et réessayez.', 'oc-lisala-core' ), '', array( 'response' => 403, 'back_link' => true ) );
	}
	if ( ! in_array( get_post_type( $post_id ), ocl_core_type_names(), true ) || ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( "Vous n'avez pas le droit de modifier ce contenu.", 'oc-lisala-core' ), '', array( 'response' => 403, 'back_link' => true ) );
	}
	update_post_meta( $post_id, '_ocl_active', ocl_core_is_active( $post_id ) ? '0' : '1' );
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=' . get_post_type( $post_id ) ) );
	exit;
}
add_action( 'admin_post_ocl_toggle_active', 'ocl_core_toggle_active' );

/**
 * Enregistre l'ordre après un glisser-déposer (AJAX).
 */
function ocl_core_save_order() {
	check_ajax_referer( 'ocl_sort', 'nonce' );
	$ids = isset( $_POST['order'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['order'] ) ) : array();
	$ids = array_filter( $ids );
	if ( ! $ids ) {
		wp_send_json_error( array( 'message' => __( 'Aucun élément à ordonner.', 'oc-lisala-core' ) ), 400 );
	}
	foreach ( $ids as $id ) {
		if ( ! in_array( get_post_type( $id ), ocl_core_type_names(), true ) || ! current_user_can( 'edit_post', $id ) ) {
			wp_send_json_error( array( 'message' => __( "Vous n'avez pas le droit de modifier ces contenus.", 'oc-lisala-core' ) ), 403 );
		}
	}
	$position = 1;
	foreach ( $ids as $id ) {
		wp_update_post(
			array(
				'ID'         => $id,
				'menu_order' => $position++,
			)
		);
	}
	wp_send_json_success();
}
add_action( 'wp_ajax_ocl_save_order', 'ocl_core_save_order' );

/**
 * Scripts et styles des écrans de l'extension.
 *
 * @param string $hook Écran.
 */
function ocl_core_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, ocl_core_type_names(), true ) && 'toplevel_page_' . OCL_ADMIN_MENU !== $hook ) {
		return;
	}
	wp_enqueue_style( 'ocl-core-admin', OCL_CORE_URL . 'assets/admin.css', array(), OCL_CORE_VERSION );
	if ( 'edit.php' === $hook ) {
		wp_enqueue_script( 'ocl-core-sort', OCL_CORE_URL . 'assets/sort.js', array( 'jquery', 'jquery-ui-sortable' ), OCL_CORE_VERSION, true );
		wp_localize_script(
			'ocl-core-sort',
			'oclSort',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ocl_sort' ),
				'saved'   => __( 'Ordre enregistré.', 'oc-lisala-core' ),
				'error'   => __( "L'ordre n'a pas pu être enregistré. Rechargez la page et réessayez.", 'oc-lisala-core' ),
			)
		);
	}
}
add_action( 'admin_enqueue_scripts', 'ocl_core_admin_assets' );

/**
 * Lien « Réglages » sur la page des extensions.
 *
 * @param array $links Liens.
 * @return array
 */
function ocl_core_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=' . OCL_ADMIN_MENU ) ) . '">' . esc_html__( 'Gestion du site', 'oc-lisala-core' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( OCL_CORE_FILE ), 'ocl_core_action_links' );
