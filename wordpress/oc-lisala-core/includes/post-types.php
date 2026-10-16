<?php
/**
 * Types de contenus : membres de l'équipe, publicités, partenaires officiels.
 *
 * @package OCLisalaCore
 */

defined( 'ABSPATH' ) || exit;

/** Slug du menu « Gestion du site » partagé avec le thème OC Lisala. */
if ( ! defined( 'OCL_ADMIN_MENU' ) ) {
	define( 'OCL_ADMIN_MENU', 'ocl-gestion' );
}

/**
 * Groupes proposés pour les membres (utilisés par les filtres de la section Équipe).
 *
 * @return array<string,string>
 */
function ocl_core_member_groups() {
	return array(
		'staff'     => __( 'Staff et encadrement', 'oc-lisala-core' ),
		'gardien'   => __( 'Gardien', 'oc-lisala-core' ),
		'defenseur' => __( 'Défenseur', 'oc-lisala-core' ),
		'milieu'    => __( 'Milieu', 'oc-lisala-core' ),
		'attaquant' => __( 'Attaquant', 'oc-lisala-core' ),
		'autre'     => __( 'Autre', 'oc-lisala-core' ),
	);
}

/**
 * Description des types de contenus et de leurs champs.
 * Les champs sont enregistrés en métadonnées (préfixe _ocl_) et décrits ici une seule fois.
 *
 * @return array
 */
function ocl_core_types() {
	return array(
		'ocl_membre'     => array(
			'labels'  => array(
				'name'                  => __( 'Équipe', 'oc-lisala-core' ),
				'singular_name'         => __( 'Membre', 'oc-lisala-core' ),
				'menu_name'             => __( 'Équipe', 'oc-lisala-core' ),
				'all_items'             => __( 'Équipe', 'oc-lisala-core' ),
				'add_new'               => __( 'Ajouter un membre', 'oc-lisala-core' ),
				'add_new_item'          => __( 'Ajouter un membre', 'oc-lisala-core' ),
				'edit_item'             => __( 'Modifier le membre', 'oc-lisala-core' ),
				'new_item'              => __( 'Nouveau membre', 'oc-lisala-core' ),
				'search_items'          => __( 'Rechercher un membre', 'oc-lisala-core' ),
				'not_found'             => __( 'Aucun membre pour le moment.', 'oc-lisala-core' ),
				'not_found_in_trash'    => __( 'Aucun membre dans la corbeille.', 'oc-lisala-core' ),
				'featured_image'        => __( 'Photo du membre', 'oc-lisala-core' ),
				'set_featured_image'    => __( 'Choisir la photo', 'oc-lisala-core' ),
				'remove_featured_image' => __( 'Retirer la photo', 'oc-lisala-core' ),
				'use_featured_image'    => __( 'Utiliser comme photo', 'oc-lisala-core' ),
			),
			'title'   => __( 'Nom complet', 'oc-lisala-core' ),
			'icon'    => 'dashicons-groups',
			'fields'  => array(
				'role'        => array(
					'label'       => __( 'Fonction ou poste', 'oc-lisala-core' ),
					'type'        => 'text',
					'placeholder' => __( 'ex. Entraîneur principal, Milieu offensif', 'oc-lisala-core' ),
					'required'    => true,
				),
				'group'       => array(
					'label'   => __( 'Groupe (filtre de la section Équipe)', 'oc-lisala-core' ),
					'type'    => 'select',
					'options' => ocl_core_member_groups(),
					'default' => 'staff',
				),
				'number'      => array(
					'label'       => __( 'Numéro de maillot (facultatif)', 'oc-lisala-core' ),
					'type'        => 'text',
					'maxlength'   => 3,
					'description' => __( 'Affiché en haut de la carte, pour les joueurs.', 'oc-lisala-core' ),
				),
				'description' => array(
					'label' => __( 'Description (facultative)', 'oc-lisala-core' ),
					'type'  => 'textarea',
				),
			),
		),
		'ocl_publicite'  => array(
			'labels' => array(
				'name'                  => __( 'Publicités', 'oc-lisala-core' ),
				'singular_name'         => __( 'Publicité', 'oc-lisala-core' ),
				'menu_name'             => __( 'Publicités', 'oc-lisala-core' ),
				'all_items'             => __( 'Publicités', 'oc-lisala-core' ),
				'add_new'               => __( 'Ajouter une publicité', 'oc-lisala-core' ),
				'add_new_item'          => __( 'Ajouter une publicité', 'oc-lisala-core' ),
				'edit_item'             => __( 'Modifier la publicité', 'oc-lisala-core' ),
				'new_item'              => __( 'Nouvelle publicité', 'oc-lisala-core' ),
				'search_items'          => __( 'Rechercher une publicité', 'oc-lisala-core' ),
				'not_found'             => __( 'Aucune publicité pour le moment.', 'oc-lisala-core' ),
				'not_found_in_trash'    => __( 'Aucune publicité dans la corbeille.', 'oc-lisala-core' ),
				'featured_image'        => __( 'Image de la publicité', 'oc-lisala-core' ),
				'set_featured_image'    => __( "Choisir l'image", 'oc-lisala-core' ),
				'remove_featured_image' => __( "Retirer l'image", 'oc-lisala-core' ),
				'use_featured_image'    => __( 'Utiliser comme image', 'oc-lisala-core' ),
			),
			'title'  => __( "Nom de l'annonceur (texte alternatif de l'image)", 'oc-lisala-core' ),
			'icon'   => 'dashicons-megaphone',
			'fields' => array(
				'url'     => array(
					'label'       => __( 'Lien de redirection', 'oc-lisala-core' ),
					'type'        => 'url',
					'placeholder' => 'https://',
					'description' => __( "Adresse ouverte au clic sur la publicité. Sans lien, l'image s'affiche sans être cliquable.", 'oc-lisala-core' ),
				),
				'new_tab' => array(
					'label'   => __( 'Ouvrir le lien dans un nouvel onglet', 'oc-lisala-core' ),
					'type'    => 'checkbox',
					'default' => '1',
				),
			),
			'image_help' => __( "Format conseillé : bannière horizontale de 1200 × 250 px (JPG, PNG ou WebP). L'image occupe toute la largeur de l'encart publicitaire de la rubrique « Le Club ».", 'oc-lisala-core' ),
		),
		'ocl_partenaire' => array(
			'labels' => array(
				'name'                  => __( 'Partenaires officiels', 'oc-lisala-core' ),
				'singular_name'         => __( 'Partenaire', 'oc-lisala-core' ),
				'menu_name'             => __( 'Partenaires officiels', 'oc-lisala-core' ),
				'all_items'             => __( 'Partenaires officiels', 'oc-lisala-core' ),
				'add_new'               => __( 'Ajouter un partenaire', 'oc-lisala-core' ),
				'add_new_item'          => __( 'Ajouter un partenaire', 'oc-lisala-core' ),
				'edit_item'             => __( 'Modifier le partenaire', 'oc-lisala-core' ),
				'new_item'              => __( 'Nouveau partenaire', 'oc-lisala-core' ),
				'search_items'          => __( 'Rechercher un partenaire', 'oc-lisala-core' ),
				'not_found'             => __( 'Aucun partenaire pour le moment.', 'oc-lisala-core' ),
				'not_found_in_trash'    => __( 'Aucun partenaire dans la corbeille.', 'oc-lisala-core' ),
				'featured_image'        => __( 'Logo du partenaire', 'oc-lisala-core' ),
				'set_featured_image'    => __( 'Choisir le logo', 'oc-lisala-core' ),
				'remove_featured_image' => __( 'Retirer le logo', 'oc-lisala-core' ),
				'use_featured_image'    => __( 'Utiliser comme logo', 'oc-lisala-core' ),
			),
			'title'  => __( 'Nom du partenaire', 'oc-lisala-core' ),
			'icon'   => 'dashicons-awards',
			'fields' => array(
				'url' => array(
					'label'       => __( 'Site web du partenaire', 'oc-lisala-core' ),
					'type'        => 'url',
					'placeholder' => 'https://',
					'description' => __( "Laisser vide pour afficher le logo sans lien.", 'oc-lisala-core' ),
				),
			),
			'image_help' => __( 'Logo de préférence en PNG ou SVG à fond transparent, environ 400 × 200 px.', 'oc-lisala-core' ),
		),
	);
}

/**
 * Enregistre les types de contenus. Ils ne sont pas consultables seuls :
 * le thème les affiche dans les sections de la page d'accueil.
 */
function ocl_core_register_post_types() {
	foreach ( ocl_core_types() as $type => $def ) {
		register_post_type(
			$type,
			array(
				'labels'              => $def['labels'],
				'public'              => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_menu'        => OCL_ADMIN_MENU,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => true,
				'menu_icon'           => $def['icon'],
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
				'hierarchical'        => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
			)
		);

		register_post_meta(
			$type,
			'_ocl_active',
			array(
				'type'          => 'string',
				'single'        => true,
				'show_in_rest'  => false,
				'auth_callback' => static function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);
		foreach ( $def['fields'] as $key => $field ) {
			register_post_meta(
				$type,
				'_ocl_' . $key,
				array(
					'type'          => 'string',
					'single'        => true,
					'show_in_rest'  => false,
					'auth_callback' => static function ( $allowed, $meta_key, $post_id ) {
						return current_user_can( 'edit_post', $post_id );
					},
				)
			);
		}
	}
}
add_action( 'init', 'ocl_core_register_post_types' );

/**
 * Un contenu est actif tant que la case « Actif » n'a pas été décochée.
 *
 * @param int $post_id Identifiant du contenu.
 * @return bool
 */
function ocl_core_is_active( $post_id ) {
	return '0' !== get_post_meta( $post_id, '_ocl_active', true );
}

/**
 * Valeur d'un champ d'un contenu du club.
 *
 * @param int    $post_id Identifiant.
 * @param string $key     Clé du champ (sans préfixe).
 * @return string
 */
function ocl_core_field( $post_id, $key ) {
	$value = get_post_meta( $post_id, '_ocl_' . $key, true );
	if ( '' === $value ) {
		$types = ocl_core_types();
		$type  = get_post_type( $post_id );
		if ( isset( $types[ $type ]['fields'][ $key ]['default'] ) ) {
			return (string) $types[ $type ]['fields'][ $key ]['default'];
		}
	}
	return (string) $value;
}

/**
 * Contenus actifs et publiés d'un type, dans l'ordre d'affichage.
 *
 * @param string $type  Type de contenu.
 * @param int    $limit Nombre maximum (-1 pour tous).
 * @return WP_Post[]
 */
function ocl_core_get_items( $type, $limit = -1 ) {
	$query = new WP_Query(
		array(
			'post_type'              => $type,
			'post_status'            => 'publish',
			'posts_per_page'         => $limit,
			'orderby'                => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'meta_query'             => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'OR',
				array(
					'key'     => '_ocl_active',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_ocl_active',
					'value'   => '0',
					'compare' => '!=',
				),
			),
		)
	);
	return $query->posts;
}
