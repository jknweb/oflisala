<?php
/**
 * Lecture et nettoyage des réglages de « Gestion du site ».
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Champs d'une page de réglages, à plat (clé => définition).
 *
 * @param string $page Clé de la page.
 * @return array
 */
function ocl_page_fields( $page ) {
	$pages  = ocl_settings_pages();
	$fields = array();
	if ( isset( $pages[ $page ] ) ) {
		foreach ( $pages[ $page ]['sections'] as $section ) {
			$fields += $section['fields'];
		}
	}
	return $fields;
}

/**
 * Valeurs par défaut d'une page.
 *
 * @param string $page Clé de la page.
 * @return array
 */
function ocl_page_defaults( $page ) {
	$defaults = array();
	foreach ( ocl_page_fields( $page ) as $key => $field ) {
		$defaults[ $key ] = isset( $field['default'] ) ? $field['default'] : '';
	}
	return $defaults;
}

/**
 * Réglages d'une page, complétés par les valeurs par défaut.
 *
 * @param string $page Clé de la page.
 * @return array
 */
function ocl_options( $page ) {
	$saved = get_option( 'ocl_' . $page, array() );
	return array_merge( ocl_page_defaults( $page ), is_array( $saved ) ? $saved : array() );
}

/**
 * Un réglage.
 *
 * @param string $page Clé de la page.
 * @param string $key  Clé du champ.
 * @return mixed
 */
function ocl_opt( $page, $key ) {
	$options = ocl_options( $page );
	return isset( $options[ $key ] ) ? $options[ $key ] : '';
}

/**
 * Choix d'un champ « select » (tableau ou fonction de rappel).
 *
 * @param array $field Définition.
 * @return array
 */
function ocl_field_choices( $field ) {
	if ( empty( $field['options'] ) ) {
		return array();
	}
	return is_callable( $field['options'] ) ? (array) call_user_func( $field['options'] ) : (array) $field['options'];
}

/**
 * Nettoie une valeur selon son type de champ.
 *
 * @param array $field Définition.
 * @param mixed $value Valeur reçue du formulaire.
 * @return mixed
 */
function ocl_sanitize_value( $field, $value ) {
	$default = isset( $field['default'] ) ? $field['default'] : '';

	switch ( $field['type'] ) {
		case 'checkbox':
			return ! empty( $value );

		case 'number':
			if ( ! is_numeric( $value ) ) {
				return $default;
			}
			$value = (int) $value;
			if ( isset( $field['min'] ) ) {
				$value = max( (int) $field['min'], $value );
			}
			if ( isset( $field['max'] ) ) {
				$value = min( (int) $field['max'], $value );
			}
			return $value;

		case 'textarea':
			$value = is_scalar( $value ) ? (string) $value : '';
			return empty( $field['html'] ) ? sanitize_textarea_field( $value ) : wp_kses(
				$value,
				array(
					'a'      => array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
					),
					'strong' => array(),
					'em'     => array(),
					'br'     => array(),
				)
			);

		case 'wysiwyg':
			return wp_kses_post( is_scalar( $value ) ? (string) $value : '' );

		case 'url':
			return esc_url_raw( is_scalar( $value ) ? trim( (string) $value ) : '', array( 'http', 'https' ) );

		case 'email':
			return sanitize_email( is_scalar( $value ) ? (string) $value : '' );

		case 'phone':
			return preg_replace( '/\D+/', '', is_scalar( $value ) ? (string) $value : '' );

		case 'select':
			$value   = is_scalar( $value ) ? (string) $value : '';
			$choices = ocl_field_choices( $field );
			return array_key_exists( $value, $choices ) ? $value : (string) $default;

		case 'image':
			$id = absint( $value );
			return ( $id && wp_attachment_is_image( $id ) ) ? $id : 0;

		case 'images':
			$ids = is_array( $value ) ? $value : explode( ',', (string) $value );
			$ids = array_values( array_filter( array_map( 'absint', $ids ), 'wp_attachment_is_image' ) );
			return isset( $field['max'] ) ? array_slice( $ids, 0, (int) $field['max'] ) : $ids;

		case 'link':
			$value = is_array( $value ) ? $value : array();
			$type  = isset( $value['type'] ) && in_array( $value['type'], array( 'whatsapp', 'url' ), true ) ? $value['type'] : 'whatsapp';
			$url   = isset( $value['url'] ) ? trim( (string) $value['url'] ) : '';
			// Ancres internes (#contact) et liens absolus ou relatifs au site.
			$url = ( '' !== $url && '#' === $url[0] ) ? '#' . sanitize_html_class( substr( $url, 1 ) ) : esc_url_raw( $url );
			return array(
				'type'    => $type,
				'url'     => $url,
				'message' => isset( $value['message'] ) ? sanitize_textarea_field( (string) $value['message'] ) : '',
			);

		case 'repeater':
			$rows  = array();
			$value = is_array( $value ) ? $value : array();
			foreach ( $value as $row ) {
				if ( ! is_array( $row ) ) {
					continue;
				}
				$clean = array();
				$empty = true;
				foreach ( $field['fields'] as $sub_key => $sub_field ) {
					$clean[ $sub_key ] = ocl_sanitize_value( $sub_field, isset( $row[ $sub_key ] ) ? $row[ $sub_key ] : null );
					if ( in_array( $sub_field['type'], array( 'text', 'textarea' ), true ) && '' !== $clean[ $sub_key ] ) {
						$empty = false;
					}
				}
				if ( ! $empty ) {
					$rows[] = $clean;
				}
			}
			return isset( $field['max'] ) ? array_slice( $rows, 0, (int) $field['max'] ) : $rows;

		default: // text.
			return sanitize_text_field( is_scalar( $value ) ? (string) $value : '' );
	}
}

/**
 * Rappel de nettoyage d'une page complète (Settings API).
 *
 * @param string $page  Clé de la page.
 * @param mixed  $input Données reçues.
 * @return array
 */
function ocl_sanitize_page( $page, $input ) {
	$input = is_array( $input ) ? $input : array();
	$clean = array();
	foreach ( ocl_page_fields( $page ) as $key => $field ) {
		$clean[ $key ] = ocl_sanitize_value( $field, isset( $input[ $key ] ) ? $input[ $key ] : null );
	}
	return $clean;
}
