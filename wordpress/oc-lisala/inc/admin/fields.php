<?php
/**
 * Affichage des champs des pages de réglages.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Identifiant HTML dérivé d'un nom de champ.
 *
 * @param string $name Nom (ex. ocl_hero[stats][0][value]).
 * @return string
 */
function ocl_field_id( $name ) {
	return trim( preg_replace( '/[^a-z0-9_-]+/i', '-', $name ), '-' );
}

/**
 * Aperçu d'une image de la médiathèque.
 *
 * @param int $id Pièce jointe.
 * @return string
 */
function ocl_admin_thumb( $id ) {
	$src = $id ? wp_get_attachment_image_url( $id, 'thumbnail' ) : '';
	return $src ? '<img src="' . esc_url( $src ) . '" alt="">' : '';
}

/**
 * Affiche un champ.
 *
 * @param string $name  Attribut name.
 * @param array  $field Définition.
 * @param mixed  $value Valeur actuelle.
 */
function ocl_render_field( $name, $field, $value ) {
	$id   = ocl_field_id( $name );
	$type = $field['type'];

	switch ( $type ) {
		case 'checkbox':
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s> %4$s</label>',
				esc_attr( $id ),
				esc_attr( $name ),
				checked( (bool) $value, true, false ),
				esc_html( $field['label'] )
			);
			break;

		case 'textarea':
			printf(
				'<textarea id="%s" name="%s" rows="%d" class="large-text">%s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				isset( $field['rows'] ) ? (int) $field['rows'] : 3,
				esc_textarea( (string) $value )
			);
			break;

		case 'wysiwyg':
			wp_editor(
				(string) $value,
				str_replace( '-', '_', $id ),
				array(
					'textarea_name' => $name,
					'textarea_rows' => 8,
					'media_buttons' => false,
					'teeny'         => true,
				)
			);
			break;

		case 'number':
			printf(
				'<input type="number" id="%s" name="%s" value="%s" class="small-text"%s%s>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value ),
				isset( $field['min'] ) ? ' min="' . (int) $field['min'] . '"' : '',
				isset( $field['max'] ) ? ' max="' . (int) $field['max'] . '"' : ''
			);
			break;

		case 'select':
			printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
			foreach ( ocl_field_choices( $field ) as $opt_value => $opt_label ) {
				printf( '<option value="%s"%s>%s</option>', esc_attr( $opt_value ), selected( (string) $value, (string) $opt_value, false ), esc_html( $opt_label ) );
			}
			echo '</select>';
			break;

		case 'image':
			$value = absint( $value );
			printf(
				'<div class="ocl-media" data-multiple="0">
					<input type="hidden" id="%1$s" name="%2$s" value="%3$s" class="ocl-media__input">
					<div class="ocl-media__preview">%4$s</div>
					<button type="button" class="button ocl-media__select">%5$s</button>
					<button type="button" class="button-link button-link-delete ocl-media__remove"%6$s>%7$s</button>
				</div>',
				esc_attr( $id ),
				esc_attr( $name ),
				$value ? esc_attr( (string) $value ) : '',
				ocl_admin_thumb( $value ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans ocl_admin_thumb().
				esc_html__( 'Choisir une image', 'oc-lisala' ),
				$value ? '' : ' hidden',
				esc_html__( "Retirer l'image", 'oc-lisala' )
			);
			break;

		case 'images':
			$ids   = array_filter( array_map( 'absint', (array) $value ) );
			$thumb = '';
			foreach ( $ids as $img ) {
				$thumb .= ocl_admin_thumb( $img );
			}
			printf(
				'<div class="ocl-media" data-multiple="1" data-max="%1$d">
					<input type="hidden" id="%2$s" name="%3$s" value="%4$s" class="ocl-media__input">
					<div class="ocl-media__preview">%5$s</div>
					<button type="button" class="button ocl-media__select">%6$s</button>
					<button type="button" class="button-link button-link-delete ocl-media__remove"%7$s>%8$s</button>
				</div>',
				isset( $field['max'] ) ? (int) $field['max'] : 0,
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( implode( ',', $ids ) ),
				$thumb, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans ocl_admin_thumb().
				esc_html__( 'Choisir les images', 'oc-lisala' ),
				$ids ? '' : ' hidden',
				esc_html__( 'Retirer les images', 'oc-lisala' )
			);
			break;

		case 'link':
			$value = wp_parse_args(
				is_array( $value ) ? $value : array(),
				array(
					'type'    => 'whatsapp',
					'url'     => '',
					'message' => '',
				)
			);
			printf( '<div class="ocl-link" data-type="%s">', esc_attr( $value['type'] ) );
			printf(
				'<p><select id="%1$s-type" name="%2$s[type]" class="ocl-link__type" aria-label="%3$s">
					<option value="whatsapp"%4$s>%5$s</option>
					<option value="url"%6$s>%7$s</option>
				</select></p>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr__( 'Type de lien', 'oc-lisala' ),
				selected( $value['type'], 'whatsapp', false ),
				esc_html__( 'Ouvrir WhatsApp avec un message prérempli', 'oc-lisala' ),
				selected( $value['type'], 'url', false ),
				esc_html__( 'Aller vers une adresse (page, article, ancre #contact…)', 'oc-lisala' )
			);
			printf(
				'<p class="ocl-link__whatsapp"><label for="%1$s-message">%3$s</label><textarea id="%1$s-message" name="%2$s[message]" rows="2" class="large-text">%4$s</textarea></p>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_html__( 'Message prérempli', 'oc-lisala' ),
				esc_textarea( $value['message'] )
			);
			printf(
				'<p class="ocl-link__url"><label for="%1$s-url">%3$s</label><input type="text" id="%1$s-url" name="%2$s[url]" value="%4$s" class="regular-text" placeholder="https://… ou #contact"></p>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_html__( 'Adresse', 'oc-lisala' ),
				esc_attr( $value['url'] )
			);
			echo '</div>';
			break;

		case 'repeater':
			$rows = is_array( $value ) ? array_values( $value ) : array();
			printf(
				'<div class="ocl-repeater" data-next="%d" data-max="%d">',
				count( $rows ),
				isset( $field['max'] ) ? (int) $field['max'] : 0
			);
			echo '<ol class="ocl-repeater__rows">';
			foreach ( $rows as $index => $row ) {
				ocl_render_repeater_row( $name, $field, (string) $index, $row );
			}
			echo '</ol>';
			echo '<script type="text/html" class="ocl-repeater__template">';
			ocl_render_repeater_row( $name, $field, '__i__', array() );
			echo '</script>';
			printf( '<button type="button" class="button ocl-repeater__add">%s</button>', esc_html( isset( $field['add_label'] ) ? $field['add_label'] : __( 'Ajouter', 'oc-lisala' ) ) );
			echo '</div>';
			break;

		default:
			$input_type = array(
				'url'   => 'url',
				'email' => 'email',
				'phone' => 'tel',
			);
			printf(
				'<input type="%s" id="%s" name="%s" value="%s" class="regular-text"%s>',
				esc_attr( isset( $input_type[ $type ] ) ? $input_type[ $type ] : 'text' ),
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value ),
				empty( $field['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $field['placeholder'] ) . '"'
			);
	}

	if ( ! empty( $field['description'] ) && 'repeater' !== $type ) {
		echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
	}
}

/**
 * Une ligne de répéteur.
 *
 * @param string $name  Nom du répéteur.
 * @param array  $field Définition du répéteur.
 * @param string $index Index (ou __i__ pour le modèle).
 * @param array  $row   Valeurs.
 */
function ocl_render_repeater_row( $name, $field, $index, $row ) {
	echo '<li class="ocl-repeater__row">';
	echo '<div class="ocl-repeater__bar"><span class="ocl-repeater__handle dashicons dashicons-move" title="' . esc_attr__( 'Glisser pour déplacer', 'oc-lisala' ) . '"></span>';
	echo '<span class="ocl-repeater__num"></span>';
	echo '<button type="button" class="button-link button-link-delete ocl-repeater__remove">' . esc_html__( 'Supprimer', 'oc-lisala' ) . '</button></div>';
	echo '<div class="ocl-repeater__fields">';
	foreach ( $field['fields'] as $sub_key => $sub_field ) {
		$sub_name = $name . '[' . $index . '][' . $sub_key . ']';
		$value    = isset( $row[ $sub_key ] ) ? $row[ $sub_key ] : ( isset( $sub_field['default'] ) ? $sub_field['default'] : '' );
		echo '<div class="ocl-repeater__field ocl-repeater__field--' . esc_attr( $sub_field['type'] ) . '">';
		if ( 'checkbox' !== $sub_field['type'] ) {
			printf( '<label for="%s">%s</label>', esc_attr( ocl_field_id( $sub_name ) ), esc_html( $sub_field['label'] ) );
		}
		ocl_render_field( $sub_name, $sub_field, $value );
		echo '</div>';
	}
	echo '</div></li>';
}
