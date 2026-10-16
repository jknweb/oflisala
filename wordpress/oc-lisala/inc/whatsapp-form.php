<?php
/**
 * Formulaire de contact vers WhatsApp : champs, validation côté serveur, construction du lien wa.me.
 *
 * Le navigateur vérifie d'abord les champs (assets/js/main.js), puis le formulaire est envoyé à
 * admin-post.php. WordPress revérifie tout ici, construit le message et redirige vers
 * https://wa.me/NUMERO?text=MESSAGE. Le visiteur appuie lui-même sur « Envoyer » dans WhatsApp :
 * rien n'est envoyé ni enregistré par le site.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/** Longueur maximale d'une réponse (le lien wa.me doit rester raisonnable). */
const OCL_FORM_MAX_LENGTH = 1500;

/**
 * Champs du formulaire, prêts à afficher et à valider.
 *
 * @return array[] name, label, type, required, minlength, options
 */
function ocl_form_fields() {
	$fields = array();
	foreach ( (array) ocl_opt( 'contact', 'fields' ) as $index => $row ) {
		if ( empty( $row['label'] ) ) {
			continue;
		}
		$type    = isset( $row['type'] ) && in_array( $row['type'], array( 'text', 'email', 'tel', 'select', 'textarea' ), true ) ? $row['type'] : 'text';
		$options = array();
		if ( 'select' === $type ) {
			$options = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $row['options'] ) ), 'strlen' ) );
			if ( ! $options ) {
				$type = 'text';
			}
		}
		$fields[] = array(
			'name'      => 'champ_' . $index,
			'label'     => $row['label'],
			'type'      => $type,
			'required'  => ! empty( $row['required'] ),
			'minlength' => isset( $row['minlength'] ) ? max( 0, (int) $row['minlength'] ) : 0,
			'options'   => $options,
		);
	}
	return $fields;
}

/**
 * Vérifie une réponse. Mêmes règles que la validation JavaScript.
 *
 * @param array  $field Champ.
 * @param string $value Valeur nettoyée.
 * @return string Message d'erreur, ou chaîne vide.
 */
function ocl_form_validate_field( $field, $value ) {
	if ( '' === $value ) {
		return $field['required'] ? __( 'Ce champ est obligatoire.', 'oc-lisala' ) : '';
	}
	if ( mb_strlen( $value ) > OCL_FORM_MAX_LENGTH ) {
		/* translators: %d: nombre de caractères. */
		return sprintf( __( 'Merci de ne pas dépasser %d caractères.', 'oc-lisala' ), OCL_FORM_MAX_LENGTH );
	}
	if ( 'email' === $field['type'] && ! is_email( $value ) ) {
		return __( "Merci d'indiquer une adresse e-mail valide.", 'oc-lisala' );
	}
	if ( 'tel' === $field['type'] && ! preg_match( '/^\+?[\d\s().-]{6,20}$/', $value ) ) {
		return __( "Merci d'indiquer un numéro de téléphone valide.", 'oc-lisala' );
	}
	if ( 'select' === $field['type'] && ! in_array( $value, $field['options'], true ) ) {
		return __( 'Merci de choisir une option de la liste.', 'oc-lisala' );
	}
	if ( $field['minlength'] && mb_strlen( $value ) < $field['minlength'] ) {
		/* translators: %d: nombre de caractères. */
		return sprintf( __( "Merci d'écrire au moins %d caractères.", 'oc-lisala' ), $field['minlength'] );
	}
	return '';
}

/**
 * Message WhatsApp construit à partir des réponses.
 *
 * @param array $fields Champs.
 * @param array $values Réponses (name => valeur).
 * @return string
 */
function ocl_form_build_message( $fields, $values ) {
	$lines = array();
	$intro = trim( (string) ocl_opt( 'contact', 'form_intro' ) );
	if ( '' !== $intro ) {
		$lines[] = $intro;
	}
	foreach ( $fields as $field ) {
		$value = isset( $values[ $field['name'] ] ) ? $values[ $field['name'] ] : '';
		if ( '' === $value ) {
			continue;
		}
		$lines[] = 'textarea' === $field['type'] ? "\n" . $field['label'] . " :\n" . $value : $field['label'] . ' : ' . $value;
	}
	return implode( "\n", $lines );
}

/**
 * Traite l'envoi du formulaire.
 */
function ocl_handle_whatsapp_form() {
	$back = wp_get_referer();
	$back = $back ? remove_query_arg( array( 'ocl_form' ), $back ) : home_url( '/' );
	$back = strtok( $back, '#' );

	// Champ piège invisible : rempli uniquement par les robots.
	if ( ! empty( $_POST['ocl_website'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- vérification anti-robot avant le nonce.
		wp_safe_redirect( $back . '#contact', 303 );
		exit;
	}

	$fields = ocl_form_fields();
	$values = array();
	$errors = array();

	$nonce_ok = isset( $_POST['ocl_form_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ocl_form_nonce'] ) ), 'ocl_whatsapp_form' );
	if ( ! $nonce_ok ) {
		$errors['_form'] = __( 'La page a expiré. Merci de vérifier vos informations et de cliquer à nouveau sur le bouton.', 'oc-lisala' );
	}

	foreach ( $fields as $field ) {
		$raw   = isset( $_POST[ $field['name'] ] ) && is_string( $_POST[ $field['name'] ] ) ? wp_unslash( $_POST[ $field['name'] ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.NonceVerification.Missing -- nettoyé juste après ; nonce vérifié plus haut.
		$value = 'textarea' === $field['type'] ? str_replace( array( "\r\n", "\r" ), "\n", sanitize_textarea_field( $raw ) ) : sanitize_text_field( $raw );
		$values[ $field['name'] ] = $value;
		$error = ocl_form_validate_field( $field, $value );
		if ( $error ) {
			$errors[ $field['name'] ] = $error;
		}
	}

	$wa = ocl_whatsapp_url( 'x' );
	if ( ! $wa ) {
		$errors['_form'] = __( "Le numéro WhatsApp du club n'est pas encore configuré. Merci de réessayer plus tard.", 'oc-lisala' );
	}

	if ( $errors ) {
		$token = wp_generate_password( 12, false );
		set_transient(
			'ocl_form_' . $token,
			array(
				'errors' => $errors,
				'values' => $values,
			),
			10 * MINUTE_IN_SECONDS
		);
		wp_safe_redirect( add_query_arg( 'ocl_form', $token, $back ) . '#contact', 303 );
		exit;
	}

	// wp_redirect() retire les « %0A » (retours à la ligne) du message : l'adresse est donc envoyée
	// directement. Elle est construite ici (numéro en chiffres + texte encodé par rawurlencode),
	// aucune injection d'en-tête n'est possible.
	nocache_headers();
	header( 'Location: ' . ocl_whatsapp_url( ocl_form_build_message( $fields, $values ) ), true, 303 );
	exit;
}
add_action( 'admin_post_nopriv_ocl_whatsapp', 'ocl_handle_whatsapp_form' );
add_action( 'admin_post_ocl_whatsapp', 'ocl_handle_whatsapp_form' );

/**
 * Erreurs et valeurs à réafficher après un envoi refusé par le serveur.
 *
 * @return array{errors:array,values:array}
 */
function ocl_form_state() {
	static $state = null;
	if ( null !== $state ) {
		return $state;
	}
	$state = array(
		'errors' => array(),
		'values' => array(),
	);
	$token = isset( $_GET['ocl_form'] ) ? sanitize_key( wp_unslash( $_GET['ocl_form'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple lecture d'un état temporaire.
	if ( $token ) {
		$saved = get_transient( 'ocl_form_' . $token );
		if ( is_array( $saved ) ) {
			$state = $saved;
		}
	}
	return $state;
}

/**
 * Valeur de départ d'un champ (après erreur, ou sujet passé dans l'adresse : ?sujet=Partenariat).
 *
 * @param array $field Champ.
 * @return string
 */
function ocl_form_initial_value( $field ) {
	$state = ocl_form_state();
	if ( isset( $state['values'][ $field['name'] ] ) ) {
		return (string) $state['values'][ $field['name'] ];
	}
	if ( 'select' === $field['type'] && isset( $_GET['sujet'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- présélection d'un choix, sans effet.
		$wanted = sanitize_text_field( wp_unslash( $_GET['sujet'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( in_array( $wanted, $field['options'], true ) ) {
			return $wanted;
		}
	}
	return '';
}
