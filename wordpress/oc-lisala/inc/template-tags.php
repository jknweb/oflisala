<?php
/**
 * Fonctions d'affichage utilisées par les gabarits.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Numéro WhatsApp du club (chiffres uniquement), ou chaîne vide.
 *
 * @return string
 */
function ocl_whatsapp_number() {
	return preg_replace( '/\D+/', '', (string) ocl_opt( 'contact', 'whatsapp' ) );
}

/**
 * Lien wa.me avec message prérempli, ou chaîne vide sans numéro.
 *
 * @param string $message Message.
 * @return string
 */
function ocl_whatsapp_url( $message = '' ) {
	$number = ocl_whatsapp_number();
	if ( '' === $number ) {
		return '';
	}
	$url = 'https://wa.me/' . $number;
	return '' === trim( $message ) ? $url : $url . '?text=' . rawurlencode( $message );
}

/**
 * Adresse d'une ancre de la page d'accueil, utilisable depuis n'importe quelle page.
 *
 * @param string $anchor Ancre sans #.
 * @return string
 */
function ocl_home_anchor( $anchor ) {
	return is_front_page() ? '#' . $anchor : home_url( '/#' . $anchor );
}

/**
 * Résout un champ « lien » : adresse et ouverture éventuelle dans un nouvel onglet.
 * Un lien WhatsApp sans numéro configuré mène au formulaire de contact.
 *
 * @param array $link Réglage (type, url, message).
 * @return array{url:string,external:bool}
 */
function ocl_resolve_link( $link ) {
	$link = wp_parse_args(
		is_array( $link ) ? $link : array(),
		array(
			'type'    => 'whatsapp',
			'url'     => '',
			'message' => '',
		)
	);

	if ( 'whatsapp' === $link['type'] ) {
		$wa = ocl_whatsapp_url( '' !== $link['message'] ? $link['message'] : ocl_opt( 'contact', 'wa_message' ) );
		return $wa ? array(
			'url'      => $wa,
			'external' => true,
		) : array(
			'url'      => ocl_home_anchor( 'contact' ),
			'external' => false,
		);
	}

	$url = trim( $link['url'] );
	if ( '' === $url ) {
		return array(
			'url'      => '',
			'external' => false,
		);
	}
	if ( '#' === $url[0] ) {
		return array(
			'url'      => ocl_home_anchor( substr( $url, 1 ) ),
			'external' => false,
		);
	}
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return array(
		'url'      => $url,
		'external' => $host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $host,
	);
}

/**
 * Bouton à partir d'un texte et d'un réglage de lien. Rien n'est affiché sans texte ou sans adresse.
 *
 * @param string $label Texte.
 * @param array  $link  Réglage du lien.
 * @param string $class Classes CSS.
 * @return string
 */
function ocl_button( $label, $link, $class = 'btn btn--primary' ) {
	$label = trim( (string) $label );
	if ( '' === $label ) {
		return '';
	}
	$target = ocl_resolve_link( $link );
	if ( '' === $target['url'] ) {
		return '';
	}
	return sprintf(
		'<a class="%s" href="%s"%s>%s</a>',
		esc_attr( $class ),
		esc_url( $target['url'] ),
		$target['external'] ? ' target="_blank" rel="noopener"' : '',
		esc_html( $label )
	);
}

/**
 * Image de la médiathèque avec la classe .photo du design d'origine.
 *
 * @param int    $id    Pièce jointe.
 * @param string $size  Taille.
 * @param array  $attr  Attributs supplémentaires.
 * @return string
 */
function ocl_image( $id, $size = 'large', $attr = array() ) {
	$id = absint( $id );
	if ( ! $id ) {
		return '';
	}
	$attr['class'] = trim( 'photo ' . ( isset( $attr['class'] ) ? $attr['class'] : '' ) );
	return wp_get_attachment_image( $id, $size, false, $attr );
}

/**
 * Logo du club (réglage, logo personnalisé WordPress ou logo officiel livré avec le thème).
 *
 * @param string $class  Classe CSS.
 * @param int    $width  Largeur.
 * @param int    $height Hauteur.
 * @param string $alt    Texte alternatif.
 * @return string
 */
function ocl_logo( $class, $width, $height, $alt ) {
	$id = absint( ocl_opt( 'general', 'logo' ) );
	if ( ! $id ) {
		$id = absint( get_theme_mod( 'custom_logo' ) );
	}
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image(
			$id,
			'medium',
			false,
			array(
				'class'    => $class,
				'alt'      => $alt,
				'loading'  => 'eager',
				'decoding' => 'async',
			)
		);
	}
	return sprintf(
		'<img class="%s" src="%s" alt="%s" width="%d" height="%d">',
		esc_attr( $class ),
		esc_url( get_template_directory_uri() . '/assets/images/logo-badge.svg' ),
		esc_attr( $alt ),
		(int) $width,
		(int) $height
	);
}

/**
 * En-tête de section : surtitre, titre, texte d'introduction.
 *
 * @param array $args eyebrow, title, intro, id, light, row.
 */
function ocl_section_head( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'eyebrow' => '',
			'title'   => '',
			'intro'   => '',
			'id'      => '',
			'light'   => false,
			'extra'   => '',
		)
	);
	if ( '' === $args['eyebrow'] && '' === $args['title'] && '' === $args['intro'] ) {
		return;
	}
	?>
	<div class="section-head section-head--row reveal">
		<div>
			<?php if ( '' !== $args['eyebrow'] ) : ?>
				<p class="eyebrow<?php echo $args['light'] ? ' eyebrow--light' : ''; ?>"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $args['title'] ) : ?>
				<h2 class="section-title<?php echo $args['light'] ? ' section-title--light' : ''; ?>" id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['title'] ); ?></h2>
			<?php endif; ?>
		</div>
		<?php if ( '' !== $args['intro'] || '' !== $args['extra'] ) : ?>
			<div class="section-head__aside">
				<?php if ( '' !== $args['intro'] ) : ?>
					<p class="section-intro"><?php echo esc_html( $args['intro'] ); ?></p>
				<?php endif; ?>
				<?php echo $args['extra']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML construit et échappé par l'appelant. ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Attribut aria-labelledby seulement si le titre existe.
 *
 * @param string $title Titre.
 * @param string $id    Identifiant du titre.
 * @return string
 */
function ocl_labelledby( $title, $id ) {
	return '' !== trim( (string) $title ) ? ' aria-labelledby="' . esc_attr( $id ) . '"' : '';
}

/**
 * Pictogrammes des réseaux sociaux (repris du site d'origine).
 *
 * @return array<string,array{0:string,1:string}>
 */
function ocl_social_networks() {
	return array(
		'facebook'  => array( 'Facebook', '<path d="M14 8h3V4h-3c-2.8 0-4 1.7-4 4.5V11H7v4h3v7h4v-7h3l1-4h-4V9c0-.6.4-1 1-1Z"/>' ),
		'instagram' => array( 'Instagram', '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2"/>' ),
		'youtube'   => array( 'YouTube', '<path d="M22 8.2a3 3 0 0 0-2.1-2.1C18 5.6 12 5.6 12 5.6s-6 0-7.9.5A3 3 0 0 0 2 8.2 31 31 0 0 0 1.6 12 31 31 0 0 0 2 15.8a3 3 0 0 0 2.1 2.1c1.9.5 7.9.5 7.9.5s6 0 7.9-.5a3 3 0 0 0 2.1-2.1c.4-1.2.4-3.8.4-3.8s0-2.6-.4-3.8ZM10 15V9l5.2 3L10 15Z"/>' ),
		'tiktok'    => array( 'TikTok', '<path d="M16.5 3c.4 2.4 1.9 3.8 4.3 4v3.3c-1.6 0-3-.4-4.3-1.2v6.1A6.3 6.3 0 1 1 10.2 9v3.4a3 3 0 1 0 2.9 3V3h3.4Z"/>' ),
	);
}

/**
 * Liste des réseaux sociaux renseignés.
 *
 * @return string
 */
function ocl_socials() {
	$items = '';
	$club  = ocl_opt( 'general', 'short_name' );
	foreach ( ocl_social_networks() as $key => $network ) {
		$url = ocl_opt( 'general', $key );
		if ( ! $url ) {
			continue;
		}
		$items .= sprintf(
			'<li><a class="social" href="%s" target="_blank" rel="noopener me" aria-label="%s"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">%s</svg></a></li>',
			esc_url( $url ),
			/* translators: 1: réseau social, 2: nom du club. */
			esc_attr( sprintf( __( '%1$s de %2$s', 'oc-lisala' ), $network[0], $club ) ),
			$network[1]
		);
	}
	return $items ? '<ul class="socials">' . $items . '</ul>' : '';
}

/**
 * Icône WhatsApp (site d'origine).
 *
 * @return string
 */
function ocl_whatsapp_icon() {
	return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.6-4-4.7-4.2-.1-.2-1.1-1.5-1.1-2.9s.7-2.1 1-2.4c.3-.3.6-.3.8-.3h.6c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .5l-.3.5-.4.5c-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6-.1l.9-1.1c.2-.3.4-.2.6-.1l2 1c.3.1.5.2.5.3.1.2.1.8-.1 1.4Z"/></svg>';
}

/**
 * Lien de téléphone.
 *
 * @param string $phone Numéro.
 * @return string
 */
function ocl_phone_link( $phone ) {
	return sprintf( '<a href="tel:%s">%s</a>', esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ), esc_html( $phone ) );
}

/**
 * Bandeau de titre des pages intérieures.
 *
 * @param string $title   Titre.
 * @param string $eyebrow Surtitre.
 * @param string $meta    HTML complémentaire (date, catégorie), déjà échappé.
 */
function ocl_page_hero( $title, $eyebrow = '', $meta = '' ) {
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'title'   => $title,
			'eyebrow' => $eyebrow,
			'meta'    => $meta,
		)
	);
}

/**
 * Lien vers la liste complète des actualités (catégorie choisie ou page des articles).
 *
 * @return string
 */
function ocl_news_archive_url() {
	$cat = (int) ocl_opt( 'actualites', 'category' );
	if ( $cat ) {
		$link = get_category_link( $cat );
		return is_wp_error( $link ) ? '' : $link;
	}
	$page = (int) get_option( 'page_for_posts' );
	return $page ? get_permalink( $page ) : '';
}

/**
 * Pagination des listes d'articles.
 */
function ocl_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => __( '← Précédents', 'oc-lisala' ),
			'next_text'          => __( 'Suivants →', 'oc-lisala' ),
			'screen_reader_text' => __( 'Navigation des articles', 'oc-lisala' ),
		)
	);
}
