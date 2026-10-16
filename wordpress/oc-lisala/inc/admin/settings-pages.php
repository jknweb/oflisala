<?php
/**
 * Menu « Gestion du site » et pages de réglages du thème.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'OCL_ADMIN_MENU' ) ) {
	define( 'OCL_ADMIN_MENU', 'ocl-gestion' );
}

/**
 * Capacité requise pour modifier les réglages du site.
 */
const OCL_CAP = 'edit_theme_options';

/**
 * Menu principal (partagé avec l'extension) et sous-pages de réglages.
 */
function ocl_admin_menu() {
	global $admin_page_hooks;
	if ( empty( $admin_page_hooks[ OCL_ADMIN_MENU ] ) ) {
		add_menu_page(
			__( 'Gestion du site', 'oc-lisala' ),
			__( 'Gestion du site', 'oc-lisala' ),
			'edit_posts',
			OCL_ADMIN_MENU,
			'ocl_render_overview',
			'dashicons-shield',
			3
		);
	} else {
		// L'extension a créé le menu : la page d'accueil du thème la remplace (elle est plus complète).
		remove_all_actions( 'toplevel_page_' . OCL_ADMIN_MENU );
		add_action( 'toplevel_page_' . OCL_ADMIN_MENU, 'ocl_render_overview' );
	}

	add_submenu_page( OCL_ADMIN_MENU, __( 'Gestion du site', 'oc-lisala' ), __( "Vue d'ensemble", 'oc-lisala' ), 'edit_posts', OCL_ADMIN_MENU, '' );

	foreach ( ocl_settings_pages() as $key => $page ) {
		add_submenu_page(
			OCL_ADMIN_MENU,
			$page['menu'],
			$page['menu'],
			OCL_CAP,
			'ocl-' . $key,
			static function () use ( $key ) {
				ocl_render_settings_page( $key );
			}
		);
	}
}
add_action( 'admin_menu', 'ocl_admin_menu', 6 );

/**
 * Remet les rubriques dans l'ordre demandé (pages du thème et listes de l'extension mélangées).
 */
function ocl_admin_menu_sort() {
	global $submenu;
	if ( empty( $submenu[ OCL_ADMIN_MENU ] ) ) {
		return;
	}
	$order = array_flip( ocl_admin_menu_order() );
	$items = $submenu[ OCL_ADMIN_MENU ];
	usort(
		$items,
		static function ( $a, $b ) use ( $order ) {
			$pa = isset( $order[ $a[2] ] ) ? $order[ $a[2] ] : 100;
			$pb = isset( $order[ $b[2] ] ) ? $order[ $b[2] ] : 100;
			return $pa - $pb;
		}
	);
	$submenu[ OCL_ADMIN_MENU ] = $items; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
add_action( 'admin_menu', 'ocl_admin_menu_sort', 999 );

/**
 * Enregistre les options (une par page) avec leur fonction de nettoyage.
 */
function ocl_register_settings() {
	foreach ( array_keys( ocl_settings_pages() ) as $key ) {
		register_setting(
			'ocl_' . $key,
			'ocl_' . $key,
			array(
				'type'              => 'array',
				'sanitize_callback' => static function ( $input ) use ( $key ) {
					return ocl_sanitize_page( $key, $input );
				},
				'default'           => array(),
				'show_in_rest'      => false,
			)
		);
		add_filter(
			'option_page_capability_ocl_' . $key,
			static function () {
				return OCL_CAP;
			}
		);
	}
}
add_action( 'admin_init', 'ocl_register_settings' );

/**
 * Affiche une page de réglages.
 *
 * @param string $key Clé de la page.
 */
function ocl_render_settings_page( $key ) {
	if ( ! current_user_can( OCL_CAP ) ) {
		wp_die( esc_html__( "Vous n'avez pas le droit de modifier ces réglages.", 'oc-lisala' ) );
	}
	$pages   = ocl_settings_pages();
	$page    = $pages[ $key ];
	$options = ocl_options( $key );
	$option  = 'ocl_' . $key;
	?>
	<div class="wrap ocl-settings">
		<h1><?php echo esc_html( $page['menu'] ); ?></h1>
		<?php if ( ! empty( $page['intro'] ) ) : ?>
			<p class="ocl-settings__intro"><?php echo esc_html( $page['intro'] ); ?></p>
		<?php endif; ?>
		<?php settings_errors(); ?>
		<?php ocl_settings_page_notes( $key ); ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" novalidate>
			<?php settings_fields( $option ); ?>

			<?php foreach ( $page['sections'] as $section ) : ?>
				<div class="ocl-panel" <?php echo empty( $section['id'] ) ? '' : 'id="' . esc_attr( $section['id'] ) . '"'; ?>>
					<h2 class="ocl-panel__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<?php if ( ! empty( $section['intro'] ) ) : ?>
						<p class="description"><?php echo esc_html( $section['intro'] ); ?></p>
					<?php endif; ?>
					<table class="form-table" role="presentation"><tbody>
						<?php foreach ( $section['fields'] as $field_key => $field ) : ?>
							<?php
							$name     = $option . '[' . $field_key . ']';
							$is_check = 'checkbox' === $field['type'];
							?>
							<tr>
								<th scope="row">
									<?php if ( ! $is_check ) : ?>
										<label for="<?php echo esc_attr( ocl_field_id( $name ) ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
									<?php endif; ?>
								</th>
								<td>
									<?php ocl_render_field( $name, $field, isset( $options[ $field_key ] ) ? $options[ $field_key ] : '' ); ?>
									<?php if ( 'repeater' === $field['type'] && ! empty( $field['description'] ) ) : ?>
										<p class="description"><?php echo esc_html( $field['description'] ); ?></p>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody></table>
				</div>
			<?php endforeach; ?>

			<div class="ocl-settings__save">
				<?php submit_button( __( 'Enregistrer les modifications', 'oc-lisala' ), 'primary large', 'submit', false ); ?>
				<a class="button button-large" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Voir le site', 'oc-lisala' ); ?></a>
			</div>
		</form>
	</div>
	<?php
}

/**
 * Rappels contextuels en haut de certaines pages.
 *
 * @param string $key Page.
 */
function ocl_settings_page_notes( $key ) {
	if ( 'actualites' === $key ) {
		printf(
			'<p><a class="button" href="%s">%s</a> <a class="button" href="%s">%s</a> <a class="button" href="%s">%s</a></p>',
			esc_url( admin_url( 'post-new.php' ) ),
			esc_html__( 'Ajouter un article', 'oc-lisala' ),
			esc_url( admin_url( 'edit.php' ) ),
			esc_html__( 'Tous les articles', 'oc-lisala' ),
			esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ),
			esc_html__( 'Catégories', 'oc-lisala' )
		);
	}
	if ( 'contact' === $key && '' === ocl_opt( 'contact', 'whatsapp' ) ) {
		echo '<div class="notice notice-warning inline"><p>' . esc_html__( "Le numéro WhatsApp n'est pas encore renseigné : les boutons WhatsApp mènent pour l'instant au formulaire de contact, et le formulaire ne peut pas être envoyé.", 'oc-lisala' ) . '</p></div>';
	}
}

/**
 * Page d'accueil « Gestion du site ».
 */
function ocl_render_overview() {
	$cards = array(
		array( 'ocl-hero', __( 'Bannière principale', 'oc-lisala' ), __( 'Image, titre, texte et boutons du haut de page ; bandeau de chiffres.', 'oc-lisala' ), OCL_CAP ),
		array( 'ocl-club', __( 'Le Club', 'oc-lisala' ), __( 'Présentation, photos et sections sportives.', 'oc-lisala' ), OCL_CAP ),
		array( 'edit.php?post_type=ocl_publicite', __( 'Publicités', 'oc-lisala' ), __( 'Bannières cliquables de la rubrique Le Club.', 'oc-lisala' ), 'edit_posts' ),
		array( 'ocl-objectifs', __( 'Objectifs du club', 'oc-lisala' ), __( 'Cartes des objectifs et bannière photo animée.', 'oc-lisala' ), OCL_CAP ),
		array( 'edit.php?post_type=ocl_membre', __( 'Équipe', 'oc-lisala' ), __( 'Joueurs et staff : photo, nom, poste, ordre, statut.', 'oc-lisala' ), 'edit_posts' ),
		array( 'ocl-actualites', __( 'Actualités', 'oc-lisala' ), __( 'Catégorie et nombre d\'articles affichés sur l\'accueil.', 'oc-lisala' ), OCL_CAP ),
		array( 'edit.php?post_type=ocl_partenaire', __( 'Partenaires officiels', 'oc-lisala' ), __( 'Logos et liens des partenaires.', 'oc-lisala' ), 'edit_posts' ),
		array( 'ocl-contact', __( 'Formulaires et WhatsApp', 'oc-lisala' ), __( 'Numéro WhatsApp, champs du formulaire, textes du contact.', 'oc-lisala' ), OCL_CAP ),
		array( 'ocl-sections', __( 'Textes des sections', 'oc-lisala' ), __( 'Titres des sections Équipe et Partenaires, encart publicitaire.', 'oc-lisala' ), OCL_CAP ),
		array( 'ocl-general', __( 'Paramètres généraux', 'oc-lisala' ), __( 'Logo, coordonnées, réseaux sociaux, pied de page.', 'oc-lisala' ), OCL_CAP ),
		array( 'nav-menus.php', __( 'Menus', 'oc-lisala' ), __( 'Menu principal et liens du pied de page.', 'oc-lisala' ), 'edit_theme_options' ),
	);
	$core = ocl_core_active();
	?>
	<div class="wrap ocl-settings">
		<h1><?php esc_html_e( 'Gestion du site', 'oc-lisala' ); ?></h1>
		<p class="ocl-settings__intro"><?php esc_html_e( 'Toutes les rubriques de la page d\'accueil se modifient ici, sans toucher au code.', 'oc-lisala' ); ?></p>
		<?php ocl_import_notice(); ?>
		<div class="ocl-cards">
			<?php foreach ( $cards as $card ) : ?>
				<?php
				if ( ! current_user_can( $card[3] ) ) {
					continue;
				}
				$is_cpt = 0 === strpos( $card[0], 'edit.php' );
				$url    = ( $is_cpt || false !== strpos( $card[0], '.php' ) ) ? admin_url( $card[0] ) : admin_url( 'admin.php?page=' . $card[0] );
				?>
				<div class="ocl-card<?php echo ( $is_cpt && ! $core ) ? ' is-disabled' : ''; ?>">
					<h2><?php echo esc_html( $card[1] ); ?></h2>
					<p><?php echo esc_html( $card[2] ); ?></p>
					<?php if ( $is_cpt && ! $core ) : ?>
						<p class="description"><?php esc_html_e( "Nécessite l'extension « OC Lisala – Contenus du club ».", 'oc-lisala' ); ?></p>
					<?php else : ?>
						<a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Modifier', 'oc-lisala' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( current_user_can( 'manage_options' ) ) : ?>
			<div class="ocl-panel">
				<h2 class="ocl-panel__title"><?php esc_html_e( "Photos du site d'origine", 'oc-lisala' ); ?></h2>
				<p><?php esc_html_e( "Importe dans la médiathèque les 7 photos d'illustration de l'ancien site (Pexels, licence libre) et les place dans les rubriques dont l'image est encore vide : bannière, Le Club, objectifs, partenaires. Vous pourrez les remplacer à tout moment par les photos du club.", 'oc-lisala' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="ocl_import_photos">
					<?php wp_nonce_field( 'ocl_import_photos' ); ?>
					<?php submit_button( __( 'Importer les photos', 'oc-lisala' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Rappel en haut des listes Équipe / Publicités / Partenaires : où modifier les textes de la section.
 */
function ocl_cpt_section_link() {
	$screen = get_current_screen();
	if ( ! $screen || 'edit' !== $screen->base ) {
		return;
	}
	$targets = array(
		'ocl_membre'     => array( 'equipe', __( 'Titre et texte de la section Équipe, filtres', 'oc-lisala' ) ),
		'ocl_publicite'  => array( 'publicites', __( "Affichage de l'encart, rotation, offre « Votre publicité ici »", 'oc-lisala' ) ),
		'ocl_partenaire' => array( 'partenaires', __( 'Bandeau, avantages et bouton de la section Partenaires', 'oc-lisala' ) ),
	);
	if ( ! isset( $targets[ $screen->post_type ] ) || ! current_user_can( OCL_CAP ) ) {
		return;
	}
	list( $anchor, $label ) = $targets[ $screen->post_type ];
	printf(
		'<div class="notice notice-info inline ocl-section-link"><p>%s <a href="%s">%s</a>. %s</p></div>',
		esc_html( $label ) . ' :',
		esc_url( admin_url( 'admin.php?page=ocl-sections#' . $anchor ) ),
		esc_html__( 'Textes des sections', 'oc-lisala' ),
		esc_html__( "Glissez les lignes (icône ≡) pour changer l'ordre d'affichage ; cliquez sur le statut pour activer ou désactiver.", 'oc-lisala' )
	);
}
add_action( 'admin_notices', 'ocl_cpt_section_link' );

/**
 * Scripts et styles des pages de réglages.
 *
 * @param string $hook Écran.
 */
function ocl_admin_assets( $hook ) {
	$is_ours = ( false !== strpos( $hook, 'ocl-' ) || 'toplevel_page_' . OCL_ADMIN_MENU === $hook );
	if ( ! $is_ours && 'nav-menus.php' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'ocl-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), OCL_THEME_VERSION );
	if ( 'nav-menus.php' === $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'ocl-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), OCL_THEME_VERSION, true );
	wp_localize_script(
		'ocl-admin',
		'oclAdmin',
		array(
			'chooseImage'  => __( 'Choisir une image', 'oc-lisala' ),
			'chooseImages' => __( 'Choisir les images', 'oc-lisala' ),
			'useImage'     => __( 'Utiliser cette image', 'oc-lisala' ),
			'useImages'    => __( 'Utiliser ces images', 'oc-lisala' ),
			'confirmRemove' => __( 'Supprimer cet élément ?', 'oc-lisala' ),
			'maxReached'   => __( 'Nombre maximum atteint.', 'oc-lisala' ),
			'maxImages'    => __( 'Seules les %d premières images seront utilisées.', 'oc-lisala' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'ocl_admin_assets' );
