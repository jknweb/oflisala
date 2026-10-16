<?php
/**
 * Description des rubriques de « Gestion du site » : pages, champs et valeurs par défaut.
 *
 * Chaque page est enregistrée dans une option « ocl_{clé} ». Les valeurs par défaut reprennent
 * les textes officiels du site d'origine ; aucune image ni coordonnée fictive n'est préremplie.
 *
 * Types de champs : text, textarea, wysiwyg, url, email, phone, number, checkbox, select,
 * image, images, link, repeater.
 *
 * @package OCLisala
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lien par défaut vers WhatsApp avec un message prérempli.
 *
 * @param string $message Message.
 * @return array
 */
function ocl_wa_default( $message ) {
	return array(
		'type'    => 'whatsapp',
		'url'     => '',
		'message' => $message,
	);
}

/**
 * Catégories d'articles proposées pour la section Actualités.
 *
 * @return array<string,string>
 */
function ocl_category_choices() {
	$choices = array( '0' => __( 'Toutes les catégories', 'oc-lisala' ) );
	$terms   = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$choices[ (string) $term->term_id ] = $term->name;
		}
	}
	return $choices;
}

/**
 * Toutes les pages de réglages, dans l'ordre du menu.
 *
 * @return array
 */
function ocl_settings_pages() {
	static $pages = null;
	if ( null !== $pages ) {
		return $pages;
	}

	$pages = array(

		/* ------------------------------------------------------------------ */
		'hero'        => array(
			'menu'     => __( 'Bannière principale', 'oc-lisala' ),
			'intro'    => __( "Grande bannière en haut de la page d'accueil, suivie du bandeau de chiffres rouge.", 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'Image de fond', 'oc-lisala' ),
					'fields' => array(
						'image'   => array(
							'type'        => 'image',
							'label'       => __( 'Image de fond', 'oc-lisala' ),
							'description' => __( "Photo horizontale d'au moins 1920 × 1080 px. Sans image, un fond vert « terrain » s'affiche.", 'oc-lisala' ),
							'default'     => 0,
						),
						'focal_x' => array(
							'type'        => 'number',
							'label'       => __( 'Cadrage horizontal (%)', 'oc-lisala' ),
							'description' => __( '0 = bord gauche, 50 = centre, 100 = bord droit. Point de la photo qui reste visible sur tous les écrans.', 'oc-lisala' ),
							'min'         => 0,
							'max'         => 100,
							'default'     => 50,
						),
						'focal_y' => array(
							'type'        => 'number',
							'label'       => __( 'Cadrage vertical (%)', 'oc-lisala' ),
							'description' => __( '0 = haut, 50 = centre, 100 = bas.', 'oc-lisala' ),
							'min'         => 0,
							'max'         => 100,
							'default'     => 50,
						),
						'zoom'    => array(
							'type'    => 'checkbox',
							'label'   => __( "Léger zoom arrière à l'ouverture de la page", 'oc-lisala' ),
							'default' => true,
						),
					),
				),
				array(
					'title'  => __( 'Textes', 'oc-lisala' ),
					'fields' => array(
						'eyebrow' => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Lisala · Mongala · RD Congo',
						),
						'badge'   => array(
							'type'        => 'text',
							'label'       => __( 'Étiquette rouge', 'oc-lisala' ),
							'description' => __( 'Nom court affiché dans le bandeau rouge incliné, au-dessus du titre.', 'oc-lisala' ),
							'default'     => 'OC Lisala',
						),
						'title_1' => array(
							'type'    => 'text',
							'label'   => __( 'Titre principal, ligne 1', 'oc-lisala' ),
							'default' => "La passion d'une ville,",
						),
						'title_2' => array(
							'type'        => 'text',
							'label'       => __( 'Titre principal, ligne 2', 'oc-lisala' ),
							'description' => __( 'Ligne soulignée du trait vert.', 'oc-lisala' ),
							'default'     => "l'ambition d'un club.",
						),
						'text'    => array(
							'type'    => 'textarea',
							'label'   => __( 'Texte descriptif', 'oc-lisala' ),
							'default' => "L'Olympique Club de Lisala porte les couleurs de sa ville sur les terrains de la Mongala. Un club de caractère, uni autour de ses joueurs, de ses supporters et de ses partenaires.",
						),
					),
				),
				array(
					'title'  => __( 'Boutons', 'oc-lisala' ),
					'intro'  => __( 'Laissez un texte de bouton vide pour masquer ce bouton.', 'oc-lisala' ),
					'fields' => array(
						'btn1_label' => array(
							'type'    => 'text',
							'label'   => __( 'Bouton principal : texte', 'oc-lisala' ),
							'default' => 'Découvrir le club',
						),
						'btn1'       => array(
							'type'    => 'link',
							'label'   => __( 'Bouton principal : lien', 'oc-lisala' ),
							'default' => ocl_wa_default( "Bonjour, je souhaite découvrir l'OC Lisala." ),
						),
						'btn2_label' => array(
							'type'    => 'text',
							'label'   => __( 'Bouton secondaire : texte', 'oc-lisala' ),
							'default' => 'Nos partenaires',
						),
						'btn2'       => array(
							'type'    => 'link',
							'label'   => __( 'Bouton secondaire : lien', 'oc-lisala' ),
							'default' => ocl_wa_default( "Bonjour, je souhaite en savoir plus sur les partenaires de l'OC Lisala." ),
						),
					),
				),
				array(
					'title'  => __( 'Bandeau de chiffres', 'oc-lisala' ),
					'fields' => array(
						'stats_enabled' => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher le bandeau rouge sous la bannière', 'oc-lisala' ),
							'default' => true,
						),
						'stats'         => array(
							'type'        => 'repeater',
							'label'       => __( 'Chiffres', 'oc-lisala' ),
							'description' => __( '4 chiffres conseillés. Un « % » final est affiché en plus petit.', 'oc-lisala' ),
							'add_label'   => __( 'Ajouter un chiffre', 'oc-lisala' ),
							'max'         => 6,
							'fields'      => array(
								'value' => array(
									'type'  => 'text',
									'label' => __( 'Chiffre', 'oc-lisala' ),
								),
								'label' => array(
									'type'  => 'text',
									'label' => __( 'Légende', 'oc-lisala' ),
								),
							),
							'default'     => array(
								array(
									'value' => '1',
									'label' => 'Ville, Lisala',
								),
								array(
									'value' => '11',
									'label' => 'Joueurs, un seul maillot',
								),
								array(
									'value' => "90'",
									'label' => "D'engagement à chaque match",
								),
								array(
									'value' => '100%',
									'label' => 'Mongala',
								),
							),
						),
					),
				),
			),
		),

		/* ------------------------------------------------------------------ */
		'club'        => array(
			'menu'     => __( 'Le Club', 'oc-lisala' ),
			'intro'    => __( 'Présentation du club, photos, sections sportives. L\'encart publicitaire de cette rubrique se gère dans « Publicités ».', 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'Présentation', 'oc-lisala' ),
					'fields' => array(
						'enabled' => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher la rubrique « Le Club »', 'oc-lisala' ),
							'default' => true,
						),
						'eyebrow' => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Le Club',
						),
						'title'   => array(
							'type'    => 'text',
							'label'   => __( 'Titre', 'oc-lisala' ),
							'default' => 'Le club de Lisala, porté par sa ville',
						),
						'lead'    => array(
							'type'    => 'textarea',
							'label'   => __( "Texte d'accroche", 'oc-lisala' ),
							'default' => "L'Olympique Club de Lisala (OC Lisala) est un club sportif de Lisala, chef-lieu de la province de la Mongala, au nord de la République démocratique du Congo.",
						),
						'content' => array(
							'type'    => 'wysiwyg',
							'label'   => __( 'Texte de présentation', 'oc-lisala' ),
							'default' => "<p>Le club compte deux sections sportives : le <strong>football</strong> et le <strong>basketball</strong>.</p>\n<p>Son identité tient en quelques mots : une ville fière, des joueurs engagés et des supporters qui vivent chaque match avec passion. Le rouge du club est celui de cette énergie.</p>\n<p>L'OC Lisala veut faire grandir le football local, révéler les talents de la région et représenter dignement la Mongala dans les compétitions.</p>",
						),
					),
				),
				array(
					'title'  => __( 'Photos', 'oc-lisala' ),
					'fields' => array(
						'image_main'  => array(
							'type'        => 'image',
							'label'       => __( 'Grande photo (portrait)', 'oc-lisala' ),
							'description' => __( 'Format vertical 4:5 conseillé (900 × 1100 px).', 'oc-lisala' ),
							'default'     => 0,
						),
						'image_small' => array(
							'type'        => 'image',
							'label'       => __( 'Petite photo (carrée)', 'oc-lisala' ),
							'description' => __( 'Format carré conseillé (600 × 600 px).', 'oc-lisala' ),
							'default'     => 0,
						),
						'badge'       => array(
							'type'        => 'textarea',
							'label'       => __( 'Étiquette rouge sur la photo', 'oc-lisala' ),
							'description' => __( 'Un retour à la ligne crée une nouvelle ligne. Laisser vide pour la masquer.', 'oc-lisala' ),
							'rows'        => 2,
							'default'     => "Rouge\n& fier",
						),
					),
				),
				array(
					'title'  => __( 'Sections sportives', 'oc-lisala' ),
					'fields' => array(
						'disciplines' => array(
							'type'      => 'repeater',
							'label'     => __( 'Sections', 'oc-lisala' ),
							'add_label' => __( 'Ajouter une section', 'oc-lisala' ),
							'max'       => 6,
							'fields'    => array(
								'label' => array(
									'type'  => 'text',
									'label' => __( 'Petit texte', 'oc-lisala' ),
								),
								'title' => array(
									'type'  => 'text',
									'label' => __( 'Nom de la section', 'oc-lisala' ),
								),
								'icon'  => array(
									'type'    => 'select',
									'label'   => __( 'Pictogramme', 'oc-lisala' ),
									'options' => 'ocl_icon_choices',
									'default' => 'football',
								),
							),
							'default'   => array(
								array(
									'label' => 'Section 01',
									'title' => 'Football',
									'icon'  => 'football',
								),
								array(
									'label' => 'Section 02',
									'title' => 'Basketball',
									'icon'  => 'basketball',
								),
							),
						),
					),
				),
			),
		),

		/* ------------------------------------------------------------------ */
		'objectifs'   => array(
			'menu'     => __( 'Objectifs du club', 'oc-lisala' ),
			'intro'    => __( 'Section sombre « Projet sportif » : cartes des objectifs et bannière photo animée.', 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'En-tête de la section', 'oc-lisala' ),
					'fields' => array(
						'enabled'  => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher la section « Objectifs du club »', 'oc-lisala' ),
							'default' => true,
						),
						'eyebrow'  => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Projet sportif',
						),
						'title'    => array(
							'type'    => 'text',
							'label'   => __( 'Titre principal', 'oc-lisala' ),
							'default' => 'Les objectifs du club',
						),
						'intro'    => array(
							'type'    => 'textarea',
							'label'   => __( 'Texte complémentaire', 'oc-lisala' ),
							'default' => "Sept objectifs guident l'action du club, sur le terrain comme en dehors.",
						),
						'bg_image' => array(
							'type'        => 'image',
							'label'       => __( 'Image de fond de la section (facultative)', 'oc-lisala' ),
							'description' => __( 'Affichée en transparence derrière les cartes, sous un voile sombre qui garde les textes lisibles.', 'oc-lisala' ),
							'default'     => 0,
						),
					),
				),
				array(
					'title'  => __( 'Objectifs', 'oc-lisala' ),
					'intro'  => __( 'Les numéros 01, 02… sont attribués automatiquement dans l\'ordre. Glissez les blocs pour les réordonner.', 'oc-lisala' ),
					'fields' => array(
						'items' => array(
							'type'      => 'repeater',
							'label'     => __( 'Objectifs', 'oc-lisala' ),
							'add_label' => __( 'Ajouter un objectif', 'oc-lisala' ),
							'max'       => 12,
							'fields'    => array(
								'icon'  => array(
									'type'    => 'select',
									'label'   => __( 'Pictogramme', 'oc-lisala' ),
									'options' => 'ocl_icon_choices',
									'default' => 'etoile',
								),
								'title' => array(
									'type'  => 'text',
									'label' => __( 'Titre', 'oc-lisala' ),
								),
								'text'  => array(
									'type'  => 'textarea',
									'label' => __( 'Texte', 'oc-lisala' ),
									'rows'  => 2,
								),
							),
							'default'   => array(
								array(
									'icon'  => 'trophee',
									'title' => 'Initiation et compétition',
									'text'  => "Organiser l'initiation, l'entraînement et la participation aux compétitions.",
								),
								array(
									'icon'  => 'pouls',
									'title' => 'Formation des pratiquants',
									'text'  => 'Assurer la formation physique, technique et morale des pratiquants.',
								),
								array(
									'icon'  => 'etoile',
									'title' => 'Détection des talents',
									'text'  => 'Détecter et accompagner les talents sportifs.',
								),
								array(
									'icon'  => 'groupe',
									'title' => 'Encadrement de la jeunesse',
									'text'  => 'Encadrer la jeunesse et favoriser son épanouissement.',
								),
								array(
									'icon'  => 'coeur',
									'title' => 'Esprit sportif',
									'text'  => "Développer la solidarité, le respect des règles et l'esprit sportif.",
								),
								array(
									'icon'  => 'progres',
									'title' => 'Formation des cadres',
									'text'  => 'Former et perfectionner les cadres techniques et administratifs.',
								),
								array(
									'icon'  => 'evenement',
									'title' => 'Manifestations sportives',
									'text'  => 'Organiser des manifestations contribuant à son objet sportif.',
								),
							),
						),
					),
				),
				array(
					'title'  => __( 'Bannière photo', 'oc-lisala' ),
					'fields' => array(
						'banner_images'    => array(
							'type'        => 'images',
							'label'       => __( 'Photos de la bannière', 'oc-lisala' ),
							'description' => __( '1 à 3 photos horizontales (1600 × 640 px). Avec plusieurs photos, elles défilent en fondu enchaîné. Sans photo, la bannière n\'est pas affichée.', 'oc-lisala' ),
							'max'         => 3,
							'default'     => array(),
						),
						'banner_line_1'    => array(
							'type'    => 'text',
							'label'   => __( 'Légende, ligne 1', 'oc-lisala' ),
							'default' => "Le travail d'aujourd'hui,",
						),
						'banner_line_2'    => array(
							'type'    => 'text',
							'label'   => __( 'Légende, ligne 2 (en gras)', 'oc-lisala' ),
							'default' => 'les victoires de demain.',
						),
						'banner_btn_label' => array(
							'type'        => 'text',
							'label'       => __( 'Bouton sur la bannière : texte (facultatif)', 'oc-lisala' ),
							'description' => __( 'Laisser vide pour ne pas afficher de bouton.', 'oc-lisala' ),
							'default'     => '',
						),
						'banner_btn'       => array(
							'type'    => 'link',
							'label'   => __( 'Bouton sur la bannière : lien', 'oc-lisala' ),
							'default' => ocl_wa_default( "Bonjour, je souhaite en savoir plus sur le projet sportif de l'OC Lisala." ),
						),
					),
				),
			),
		),

		/* ------------------------------------------------------------------ */
		'actualites'  => array(
			'menu'     => __( 'Actualités', 'oc-lisala' ),
			'intro'    => __( 'La section affiche automatiquement les derniers articles publiés. Pour écrire un article : Articles > Ajouter un article (titre, texte, extrait, image mise en avant, catégorie).', 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'Articles affichés', 'oc-lisala' ),
					'fields' => array(
						'enabled'  => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher la section « Actualités » sur l\'accueil', 'oc-lisala' ),
							'default' => true,
						),
						'category' => array(
							'type'        => 'select',
							'label'       => __( 'Catégorie à afficher', 'oc-lisala' ),
							'description' => __( 'Seuls les articles de cette catégorie apparaissent sur l\'accueil. Les catégories se créent dans Articles > Catégories.', 'oc-lisala' ),
							'options'     => 'ocl_category_choices',
							'default'     => '0',
						),
						'count'    => array(
							'type'        => 'number',
							'label'       => __( "Nombre d'articles visibles", 'oc-lisala' ),
							'description' => __( '4 reproduit la mise en page d\'origine : un grand article à gauche, trois à droite.', 'oc-lisala' ),
							'min'         => 1,
							'max'         => 12,
							'default'     => 4,
						),
					),
				),
				array(
					'title'  => __( 'Contenu des cartes', 'oc-lisala' ),
					'fields' => array(
						'show_image'    => array(
							'type'    => 'checkbox',
							'label'   => __( "Image mise en avant (fond « terrain » si l'article n'en a pas)", 'oc-lisala' ),
							'default' => true,
						),
						'show_category' => array(
							'type'    => 'checkbox',
							'label'   => __( 'Catégorie sur l\'image', 'oc-lisala' ),
							'default' => true,
						),
						'show_date'     => array(
							'type'    => 'checkbox',
							'label'   => __( 'Date de publication', 'oc-lisala' ),
							'default' => true,
						),
						'show_excerpt'  => array(
							'type'    => 'checkbox',
							'label'   => __( 'Extrait', 'oc-lisala' ),
							'default' => true,
						),
						'read_more'     => array(
							'type'    => 'text',
							'label'   => __( 'Texte du lien vers l\'article', 'oc-lisala' ),
							'default' => "Lire l'article",
						),
					),
				),
				array(
					'title'  => __( 'Textes de la section', 'oc-lisala' ),
					'fields' => array(
						'eyebrow'    => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Actualités',
						),
						'title'      => array(
							'type'    => 'text',
							'label'   => __( 'Titre', 'oc-lisala' ),
							'default' => 'La vie du club',
						),
						'intro'      => array(
							'type'    => 'textarea',
							'label'   => __( "Texte d'introduction (facultatif)", 'oc-lisala' ),
							'default' => '',
						),
						'all_label'  => array(
							'type'        => 'text',
							'label'       => __( 'Lien « toutes les actualités »', 'oc-lisala' ),
							'description' => __( 'Mène à la catégorie choisie (ou à la page des articles). Laisser vide pour le masquer.', 'oc-lisala' ),
							'default'     => 'Toutes les actualités',
						),
						'empty_text' => array(
							'type'    => 'text',
							'label'   => __( 'Message quand aucun article n\'est publié', 'oc-lisala' ),
							'default' => 'Les actualités du club seront publiées ici.',
						),
					),
				),
			),
		),

		/* ------------------------------------------------------------------ */
		'sections'    => array(
			'menu'     => __( 'Textes des sections', 'oc-lisala' ),
			'intro'    => __( "Titres et textes des sections Équipe, Partenaires et de l'encart publicitaire. Les membres, publicités et partenaires eux-mêmes se gèrent dans leurs rubriques.", 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'Section Équipe', 'oc-lisala' ),
					'id'     => 'equipe',
					'fields' => array(
						'team_enabled' => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher la section « Équipe »', 'oc-lisala' ),
							'default' => true,
						),
						'team_eyebrow' => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Équipe',
						),
						'team_title'   => array(
							'type'    => 'text',
							'label'   => __( 'Titre', 'oc-lisala' ),
							'default' => 'Joueurs et staff',
						),
						'team_intro'   => array(
							'type'    => 'textarea',
							'label'   => __( "Texte d'introduction (facultatif)", 'oc-lisala' ),
							'default' => '',
						),
						'team_filters' => array(
							'type'    => 'checkbox',
							'label'   => __( 'Boutons de filtre (Staff, Gardiens, Défenseurs…)', 'oc-lisala' ),
							'default' => true,
						),
						'team_empty'   => array(
							'type'    => 'text',
							'label'   => __( "Message quand aucun membre n'est actif", 'oc-lisala' ),
							'default' => "La présentation de l'effectif sera publiée ici.",
						),
					),
				),
				array(
					'title'  => __( 'Encart publicitaire (rubrique Le Club)', 'oc-lisala' ),
					'id'     => 'publicites',
					'fields' => array(
						'ads_enabled'      => array(
							'type'    => 'checkbox',
							'label'   => __( "Afficher l'encart publicitaire", 'oc-lisala' ),
							'default' => true,
						),
						'ads_delay'        => array(
							'type'        => 'number',
							'label'       => __( 'Rotation des publicités (secondes)', 'oc-lisala' ),
							'description' => __( 'Avec plusieurs publicités actives, elles se succèdent au même emplacement.', 'oc-lisala' ),
							'min'         => 3,
							'max'         => 60,
							'default'     => 6,
						),
						'ads_fallback'     => array(
							'type'    => 'checkbox',
							'label'   => __( 'Sans publicité active, afficher l\'offre « Votre publicité ici »', 'oc-lisala' ),
							'default' => true,
						),
						'ads_label'        => array(
							'type'    => 'text',
							'label'   => __( 'Offre : petit texte', 'oc-lisala' ),
							'default' => 'Espace publicitaire',
						),
						'ads_title'        => array(
							'type'    => 'text',
							'label'   => __( 'Offre : titre', 'oc-lisala' ),
							'default' => 'Votre publicité ici',
						),
						'ads_text'         => array(
							'type'    => 'text',
							'label'   => __( 'Offre : texte', 'oc-lisala' ),
							'default' => "Bannière visible par tous les visiteurs du site de l'OC Lisala.",
						),
						'ads_btn_label'    => array(
							'type'    => 'text',
							'label'   => __( 'Offre : texte du bouton', 'oc-lisala' ),
							'default' => 'Réserver cet espace',
						),
						'ads_btn'          => array(
							'type'    => 'link',
							'label'   => __( 'Offre : lien du bouton', 'oc-lisala' ),
							'default' => ocl_wa_default( "Bonjour, je souhaite réserver l'espace publicitaire du site de l'OC Lisala." ),
						),
					),
				),
				array(
					'title'  => __( 'Section Partenaires', 'oc-lisala' ),
					'id'     => 'partenaires',
					'fields' => array(
						'partners_enabled'   => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher la section « Partenaires »', 'oc-lisala' ),
							'default' => true,
						),
						'partners_image'     => array(
							'type'        => 'image',
							'label'       => __( 'Image de fond du bandeau rouge', 'oc-lisala' ),
							'description' => __( 'Photo horizontale, affichée sous un voile rouge.', 'oc-lisala' ),
							'default'     => 0,
						),
						'partners_eyebrow'   => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Partenaires',
						),
						'partners_title'     => array(
							'type'    => 'text',
							'label'   => __( 'Titre', 'oc-lisala' ),
							'default' => "Soutenir l'OC Lisala, c'est soutenir toute une ville",
						),
						'partners_lead'      => array(
							'type'    => 'textarea',
							'label'   => __( 'Texte', 'oc-lisala' ),
							'default' => "Associer votre marque à l'OC Lisala, c'est être présent auprès d'un public fidèle et participer concrètement au développement du sport et de la jeunesse dans la Mongala.",
						),
						'partners_btn_label' => array(
							'type'    => 'text',
							'label'   => __( 'Bouton : texte', 'oc-lisala' ),
							'default' => 'Devenir partenaire',
						),
						'partners_btn'       => array(
							'type'    => 'link',
							'label'   => __( 'Bouton : lien', 'oc-lisala' ),
							'default' => ocl_wa_default( "Bonjour, je souhaite devenir partenaire de l'OC Lisala." ),
						),
						'benefits'           => array(
							'type'      => 'repeater',
							'label'     => __( 'Avantages proposés', 'oc-lisala' ),
							'add_label' => __( 'Ajouter un avantage', 'oc-lisala' ),
							'max'       => 6,
							'fields'    => array(
								'title' => array(
									'type'  => 'text',
									'label' => __( 'Titre', 'oc-lisala' ),
								),
								'text'  => array(
									'type'  => 'textarea',
									'label' => __( 'Texte', 'oc-lisala' ),
									'rows'  => 2,
								),
							),
							'default'   => array(
								array(
									'title' => 'Visibilité locale',
									'text'  => 'Votre marque sur les maillots, au bord du terrain, sur le site et les réseaux du club.',
								),
								array(
									'title' => 'Ancrage dans la Mongala',
									'text'  => 'Un lien direct avec les habitants de Lisala et une image positive dans toute la province.',
								),
								array(
									'title' => 'Impact sur la jeunesse',
									'text'  => 'Un soutien utile : équipements, encadrement, déplacements et formation des jeunes talents.',
								),
							),
						),
						'logos_title'        => array(
							'type'    => 'text',
							'label'   => __( 'Titre au-dessus des logos', 'oc-lisala' ),
							'default' => 'Partenaires officiels',
						),
						'logos_btn_label'    => array(
							'type'        => 'text',
							'label'       => __( 'Bouton sous les logos : texte', 'oc-lisala' ),
							'description' => __( 'Utilise le même lien que le bouton du bandeau. Laisser vide pour le masquer.', 'oc-lisala' ),
							'default'     => 'Devenir partenaire',
						),
					),
				),
			),
		),

		/* ------------------------------------------------------------------ */
		'contact'     => array(
			'menu'     => __( 'Formulaires et WhatsApp', 'oc-lisala' ),
			'intro'    => __( 'Le formulaire de contact et tous les boutons réglés sur « WhatsApp » ouvrent une conversation avec ce numéro. Le visiteur doit encore appuyer sur Envoyer dans WhatsApp : aucun message n\'est envoyé automatiquement.', 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'WhatsApp', 'oc-lisala' ),
					'fields' => array(
						'whatsapp'   => array(
							'type'        => 'phone',
							'label'       => __( 'Numéro WhatsApp de destination', 'oc-lisala' ),
							'description' => __( 'Format international avec indicatif, par exemple 243 812 345 678 pour la RD Congo. Les espaces, + et tirets sont retirés automatiquement. Tant que le numéro est vide, les boutons WhatsApp mènent au formulaire de contact.', 'oc-lisala' ),
							'placeholder' => '243…',
							'default'     => '',
						),
						'wa_message' => array(
							'type'        => 'textarea',
							'label'       => __( "Message d'introduction", 'oc-lisala' ),
							'description' => __( 'Message prérempli du bouton WhatsApp flottant et du bouton « Écrire sur WhatsApp ».', 'oc-lisala' ),
							'rows'        => 2,
							'default'     => "Bonjour, je vous contacte depuis le site de l'OC Lisala.",
						),
						'float'      => array(
							'type'    => 'checkbox',
							'label'   => __( 'Bouton WhatsApp flottant en bas à droite de l\'écran', 'oc-lisala' ),
							'default' => true,
						),
					),
				),
				array(
					'title'  => __( 'Section Contact', 'oc-lisala' ),
					'fields' => array(
						'enabled'      => array(
							'type'    => 'checkbox',
							'label'   => __( 'Afficher la section « Contact » sur l\'accueil', 'oc-lisala' ),
							'default' => true,
						),
						'eyebrow'      => array(
							'type'    => 'text',
							'label'   => __( 'Surtitre', 'oc-lisala' ),
							'default' => 'Contact',
						),
						'title'        => array(
							'type'    => 'text',
							'label'   => __( 'Titre', 'oc-lisala' ),
							'default' => 'Parlons football',
						),
						'text'         => array(
							'type'    => 'textarea',
							'label'   => __( 'Texte', 'oc-lisala' ),
							'default' => "Supporters, joueurs, partenaires ou médias : écrivez à l'OC Lisala.",
						),
						'wa_btn_label' => array(
							'type'        => 'text',
							'label'       => __( 'Bouton vert « WhatsApp » : texte', 'oc-lisala' ),
							'description' => __( 'Affiché seulement si le numéro WhatsApp est renseigné. Laisser vide pour le masquer.', 'oc-lisala' ),
							'default'     => 'Écrire sur WhatsApp',
						),
						'social_title' => array(
							'type'    => 'text',
							'label'   => __( 'Titre au-dessus des réseaux sociaux', 'oc-lisala' ),
							'default' => 'Suivre le club',
						),
					),
				),
				array(
					'title'  => __( 'Formulaire', 'oc-lisala' ),
					'fields' => array(
						'form_title'    => array(
							'type'    => 'text',
							'label'   => __( 'Titre du formulaire', 'oc-lisala' ),
							'default' => 'Envoyer un message',
						),
						'form_note'     => array(
							'type'    => 'text',
							'label'   => __( 'Note sous le titre', 'oc-lisala' ),
							'default' => "Votre message s'ouvrira dans WhatsApp, prêt à être envoyé au club.",
						),
						'form_intro'    => array(
							'type'        => 'textarea',
							'label'       => __( 'Première ligne du message WhatsApp', 'oc-lisala' ),
							'description' => __( 'Suivie des réponses du visiteur, une ligne par champ.', 'oc-lisala' ),
							'rows'        => 2,
							'default'     => "Bonjour, message envoyé depuis le site de l'OC Lisala.",
						),
						'fields'        => array(
							'type'        => 'repeater',
							'label'       => __( 'Champs du formulaire', 'oc-lisala' ),
							'description' => __( 'Pour une liste déroulante, écrivez un choix par ligne dans « Choix ».', 'oc-lisala' ),
							'add_label'   => __( 'Ajouter un champ', 'oc-lisala' ),
							'max'         => 12,
							'fields'      => array(
								'label'     => array(
									'type'  => 'text',
									'label' => __( 'Intitulé', 'oc-lisala' ),
								),
								'type'      => array(
									'type'    => 'select',
									'label'   => __( 'Type', 'oc-lisala' ),
									'options' => array(
										'text'     => __( 'Texte court', 'oc-lisala' ),
										'email'    => __( 'Adresse e-mail', 'oc-lisala' ),
										'tel'      => __( 'Téléphone', 'oc-lisala' ),
										'select'   => __( 'Liste déroulante', 'oc-lisala' ),
										'textarea' => __( 'Texte long', 'oc-lisala' ),
									),
									'default' => 'text',
								),
								'required'  => array(
									'type'    => 'checkbox',
									'label'   => __( 'Obligatoire', 'oc-lisala' ),
									'default' => false,
								),
								'minlength' => array(
									'type'    => 'number',
									'label'   => __( 'Nombre minimum de caractères', 'oc-lisala' ),
									'min'     => 0,
									'max'     => 500,
									'default' => 0,
								),
								'options'   => array(
									'type'  => 'textarea',
									'label' => __( 'Choix (liste déroulante)', 'oc-lisala' ),
									'rows'  => 3,
								),
							),
							'default'     => array(
								array(
									'label'     => 'Nom',
									'type'      => 'text',
									'required'  => true,
									'minlength' => 2,
									'options'   => '',
								),
								array(
									'label'     => 'Adresse e-mail',
									'type'      => 'email',
									'required'  => true,
									'minlength' => 0,
									'options'   => '',
								),
								array(
									'label'     => 'Sujet',
									'type'      => 'select',
									'required'  => true,
									'minlength' => 0,
									'options'   => "Partenariat\nSupporters\nRejoindre l'équipe\nPresse et médias\nAutre",
								),
								array(
									'label'     => 'Message',
									'type'      => 'textarea',
									'required'  => true,
									'minlength' => 10,
									'options'   => '',
								),
							),
						),
						'submit_label'  => array(
							'type'    => 'text',
							'label'   => __( "Libellé du bouton d'envoi", 'oc-lisala' ),
							'default' => 'Envoyer sur WhatsApp',
						),
						'success_text'  => array(
							'type'    => 'text',
							'label'   => __( 'Message affiché après le clic', 'oc-lisala' ),
							'default' => "Merci ! WhatsApp s'ouvre avec votre message : il ne reste plus qu'à appuyer sur Envoyer.",
						),
					),
				),
			),
		),

		/* ------------------------------------------------------------------ */
		'general'     => array(
			'menu'     => __( 'Paramètres généraux', 'oc-lisala' ),
			'intro'    => __( 'Identité du club, coordonnées, réseaux sociaux et pied de page. Les menus se modifient dans Apparence > Menus, l\'icône du site dans Réglages > Général.', 'oc-lisala' ),
			'sections' => array(
				array(
					'title'  => __( 'Identité', 'oc-lisala' ),
					'fields' => array(
						'logo'             => array(
							'type'        => 'image',
							'label'       => __( 'Logo', 'oc-lisala' ),
							'description' => __( 'Version claire, lisible sur fond sombre (en-tête et pied de page). Sans image, le logo officiel fourni avec le thème est utilisé.', 'oc-lisala' ),
							'default'     => 0,
						),
						'short_name'       => array(
							'type'    => 'text',
							'label'   => __( 'Nom court', 'oc-lisala' ),
							'default' => 'OC Lisala',
						),
						'full_name'        => array(
							'type'    => 'text',
							'label'   => __( 'Nom complet', 'oc-lisala' ),
							'default' => 'Olympique Club de Lisala',
						),
						'motto'            => array(
							'type'    => 'text',
							'label'   => __( 'Devise (pied de page)', 'oc-lisala' ),
							'default' => "La passion d'une ville, l'ambition d'un club",
						),
						'meta_description' => array(
							'type'        => 'textarea',
							'label'       => __( 'Description pour les moteurs de recherche', 'oc-lisala' ),
							'description' => __( 'Résumé de 150 caractères environ, affiché par Google sous le nom du site.', 'oc-lisala' ),
							'rows'        => 2,
							'default'     => "Site officiel de l'Olympique Club de Lisala (OC Lisala), club sportif de Lisala, province de la Mongala, RD Congo : le club, ses objectifs, l'équipe, les actualités et les partenaires.",
						),
					),
				),
				array(
					'title'  => __( 'Coordonnées', 'oc-lisala' ),
					'intro'  => __( 'Une coordonnée vide n\'est pas affichée.', 'oc-lisala' ),
					'fields' => array(
						'address' => array(
							'type'    => 'textarea',
							'label'   => __( 'Adresse', 'oc-lisala' ),
							'rows'    => 2,
							'default' => 'N° 1, avenue du Port, commune de Bolikango, Lisala, Mongala, RD Congo',
						),
						'phone'   => array(
							'type'    => 'text',
							'label'   => __( 'Téléphone', 'oc-lisala' ),
							'default' => '',
						),
						'email'   => array(
							'type'    => 'email',
							'label'   => __( 'E-mail', 'oc-lisala' ),
							'default' => '',
						),
					),
				),
				array(
					'title'  => __( 'Réseaux sociaux', 'oc-lisala' ),
					'intro'  => __( 'Adresse complète de chaque page (https://…). Un réseau sans adresse n\'est pas affiché.', 'oc-lisala' ),
					'fields' => array(
						'facebook'  => array(
							'type'    => 'url',
							'label'   => 'Facebook',
							'default' => '',
						),
						'instagram' => array(
							'type'    => 'url',
							'label'   => 'Instagram',
							'default' => '',
						),
						'youtube'   => array(
							'type'    => 'url',
							'label'   => 'YouTube',
							'default' => '',
						),
						'tiktok'    => array(
							'type'    => 'url',
							'label'   => 'TikTok',
							'default' => '',
						),
					),
				),
				array(
					'title'  => __( 'Pied de page', 'oc-lisala' ),
					'fields' => array(
						'footer_title_1' => array(
							'type'        => 'text',
							'label'       => __( 'Titre de la 1re colonne de liens', 'oc-lisala' ),
							'description' => __( 'Liens : menu placé à l\'emplacement « Pied de page, colonne 1 ».', 'oc-lisala' ),
							'default'     => 'Le site',
						),
						'footer_title_2' => array(
							'type'        => 'text',
							'label'       => __( 'Titre de la 2e colonne de liens', 'oc-lisala' ),
							'description' => __( 'Liens : menu placé à l\'emplacement « Pied de page, colonne 2 ».', 'oc-lisala' ),
							'default'     => 'Rejoindre',
						),
						'footer_title_3' => array(
							'type'    => 'text',
							'label'   => __( 'Titre de la colonne Contact', 'oc-lisala' ),
							'default' => 'Contact',
						),
						'copyright'      => array(
							'type'        => 'text',
							'label'       => __( 'Mention de copyright', 'oc-lisala' ),
							'description' => __( "Précédée automatiquement de « © » et de l'année en cours.", 'oc-lisala' ),
							'default'     => 'Olympique Club de Lisala. Tous droits réservés.',
						),
						'credits'        => array(
							'type'        => 'textarea',
							'label'       => __( 'Crédits photo (facultatif)', 'oc-lisala' ),
							'description' => __( 'Liens autorisés. Rempli automatiquement si vous importez les photos du site d\'origine.', 'oc-lisala' ),
							'rows'        => 2,
							'html'        => true,
							'default'     => '',
						),
					),
				),
			),
		),
	);

	return $pages;
}

/**
 * Ordre des rubriques dans le menu « Gestion du site » (pages du thème et types de l'extension).
 *
 * @return string[]
 */
function ocl_admin_menu_order() {
	return array(
		'ocl-gestion',
		'ocl-hero',
		'ocl-club',
		'edit.php?post_type=ocl_publicite',
		'ocl-objectifs',
		'edit.php?post_type=ocl_membre',
		'ocl-actualites',
		'edit.php?post_type=ocl_partenaire',
		'ocl-contact',
		'ocl-sections',
		'ocl-general',
	);
}
