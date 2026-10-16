<?php
/**
 * Boîtes de champs des membres, publicités et partenaires.
 *
 * @package OCLisalaCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajoute la boîte « Informations » à chaque type de contenu du club.
 */
function ocl_core_add_meta_boxes() {
	foreach ( ocl_core_types() as $type => $def ) {
		add_meta_box(
			'ocl_details',
			__( 'Informations', 'oc-lisala-core' ),
			'ocl_core_render_meta_box',
			$type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'ocl_core_add_meta_boxes' );

/**
 * Libellé du champ titre (« Nom complet », « Nom du partenaire »…).
 *
 * @param string  $text Texte par défaut.
 * @param WP_Post $post Contenu.
 * @return string
 */
function ocl_core_title_placeholder( $text, $post ) {
	$types = ocl_core_types();
	return isset( $types[ $post->post_type ] ) ? $types[ $post->post_type ]['title'] : $text;
}
add_filter( 'enter_title_here', 'ocl_core_title_placeholder', 10, 2 );

/**
 * Aide sous la boîte d'image (format conseillé).
 *
 * @param string   $html    HTML de la boîte.
 * @param int      $post_id Contenu.
 * @return string
 */
function ocl_core_thumbnail_help( $html, $post_id ) {
	$types = ocl_core_types();
	$type  = get_post_type( $post_id );
	if ( ! empty( $types[ $type ]['image_help'] ) ) {
		$html .= '<p class="description">' . esc_html( $types[ $type ]['image_help'] ) . '</p>';
	}
	return $html;
}
add_filter( 'admin_post_thumbnail_html', 'ocl_core_thumbnail_help', 10, 2 );

/**
 * Affiche les champs.
 *
 * @param WP_Post $post Contenu en cours.
 */
function ocl_core_render_meta_box( $post ) {
	$types = ocl_core_types();
	$def   = $types[ $post->post_type ];
	wp_nonce_field( 'ocl_save_' . $post->post_type, 'ocl_core_nonce' );

	$is_new = 'auto-draft' === $post->post_status;
	$active = $is_new ? true : ocl_core_is_active( $post->ID );

	echo '<table class="form-table ocl-fields" role="presentation"><tbody>';
	foreach ( $def['fields'] as $key => $field ) {
		$id    = 'ocl_' . $key;
		$name  = 'ocl[' . $key . ']';
		$value = $is_new && isset( $field['default'] ) ? (string) $field['default'] : ocl_core_field( $post->ID, $key );

		echo '<tr><th scope="row">';
		if ( 'checkbox' !== $field['type'] ) {
			printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
		}
		echo '</th><td>';

		switch ( $field['type'] ) {
			case 'select':
				printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $field['options'] as $opt_value => $opt_label ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $opt_value ), selected( $value, $opt_value, false ), esc_html( $opt_label ) );
				}
				echo '</select>';
				break;
			case 'textarea':
				printf( '<textarea id="%s" name="%s" rows="4" class="large-text">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( $value ) );
				break;
			case 'checkbox':
				printf(
					'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s> %4$s</label>',
					esc_attr( $id ),
					esc_attr( $name ),
					checked( '1', $value, false ),
					esc_html( $field['label'] )
				);
				break;
			default:
				printf(
					'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text"%5$s%6$s%7$s>',
					'url' === $field['type'] ? 'url' : 'text',
					esc_attr( $id ),
					esc_attr( $name ),
					'url' === $field['type'] ? esc_url( $value ) : esc_attr( $value ),
					empty( $field['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $field['placeholder'] ) . '"',
					empty( $field['maxlength'] ) ? '' : ' maxlength="' . absint( $field['maxlength'] ) . '"',
					empty( $field['required'] ) ? '' : ' required'
				);
		}
		if ( ! empty( $field['description'] ) ) {
			echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
		}
		echo '</td></tr>';
	}

	echo '<tr><th scope="row">' . esc_html__( 'Statut', 'oc-lisala-core' ) . '</th><td>';
	printf(
		'<label for="ocl_active"><input type="checkbox" id="ocl_active" name="ocl[active]" value="1"%s> %s</label>',
		checked( $active, true, false ),
		esc_html__( 'Actif : afficher sur le site', 'oc-lisala-core' )
	);
	echo '<p class="description">' . esc_html__( "Décochez pour masquer ce contenu sans le supprimer. L'ordre d'affichage se règle dans « Attributs » ou par glisser-déposer dans la liste.", 'oc-lisala-core' ) . '</p>';
	echo '</td></tr>';
	echo '</tbody></table>';
}

/**
 * Nettoie une valeur selon le type de champ.
 *
 * @param array  $field Définition.
 * @param string $value Valeur brute.
 * @return string
 */
function ocl_core_sanitize_field( $field, $value ) {
	switch ( $field['type'] ) {
		case 'url':
			return esc_url_raw( trim( $value ), array( 'http', 'https' ) );
		case 'textarea':
			return sanitize_textarea_field( $value );
		case 'select':
			return array_key_exists( $value, $field['options'] ) ? $value : (string) ( $field['default'] ?? '' );
		case 'checkbox':
			return '1' === $value ? '1' : '0';
		default:
			$value = sanitize_text_field( $value );
			return empty( $field['maxlength'] ) ? $value : mb_substr( $value, 0, (int) $field['maxlength'] );
	}
}

/**
 * Enregistre les champs (nonce, droits, nettoyage).
 *
 * @param int     $post_id Contenu.
 * @param WP_Post $post    Objet.
 */
function ocl_core_save_meta_box( $post_id, $post ) {
	$types = ocl_core_types();
	if ( ! isset( $types[ $post->post_type ] ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['ocl_core_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ocl_core_nonce'] ) ), 'ocl_save_' . $post->post_type ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$input = isset( $_POST['ocl'] ) && is_array( $_POST['ocl'] ) ? wp_unslash( $_POST['ocl'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- nettoyé champ par champ ci-dessous.

	foreach ( $types[ $post->post_type ]['fields'] as $key => $field ) {
		$raw = isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ? (string) $input[ $key ] : '';
		update_post_meta( $post_id, '_ocl_' . $key, ocl_core_sanitize_field( $field, $raw ) );
	}
	update_post_meta( $post_id, '_ocl_active', ( isset( $input['active'] ) && '1' === $input['active'] ) ? '1' : '0' );
}
add_action( 'save_post', 'ocl_core_save_meta_box', 10, 2 );
