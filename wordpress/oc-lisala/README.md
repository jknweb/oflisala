# Thème WordPress OC Lisala

Thème officiel de l'Olympique Club de Lisala. Il reprend le design du site d'origine (rouge `#A3060F`, noir `#1C1C1E`, vert terrain, polices Teko et Inter, animations) et rend chaque section modifiable depuis le tableau de bord, sans toucher au code.

- WordPress 6.2 ou plus récent, PHP 7.4 ou plus récent.
- Aucune extension payante. L'extension « OC Lisala – Contenus du club » (gratuite, fournie avec le thème) gère l'équipe, les publicités et les partenaires : ces contenus restent enregistrés même si vous changez de thème.

## 1. Installation

1. **Apparence > Thèmes > Ajouter un thème > Téléverser un thème**, choisir `oc-lisala.zip`, puis **Installer** et **Activer**.
2. Un bandeau jaune apparaît en haut du tableau de bord : cliquer sur **Installer et activer l'extension**. (Autre méthode : **Extensions > Ajouter > Téléverser une extension** avec `oc-lisala-core.zip`.)
3. Ouvrir **Gestion du site** dans le menu de gauche.
4. **Réglages > Permaliens** : choisir « Titre de la publication » puis enregistrer (adresses lisibles pour les articles).
5. **Réglages > Général** : vérifier le titre du site, la langue « Français » et le fuseau horaire (Kinshasa). Choisir l'icône du site (favicon) ; sans icône, celle du club est utilisée.

À l'activation, le thème crée trois menus (menu principal et deux colonnes du pied de page) qui pointent vers les sections de l'accueil. Ils se modifient dans **Apparence > Menus**.

### Démarrage rapide

| Étape | Où |
| --- | --- |
| Numéro WhatsApp du club (obligatoire pour le formulaire et les boutons WhatsApp) | Gestion du site > Formulaires et WhatsApp |
| Photos de la bannière, du club, des objectifs et des partenaires | Pages correspondantes de Gestion du site, ou bouton **Importer les photos** de la Vue d'ensemble (reprend les 7 photos libres de droits de l'ancien site) |
| Joueurs et staff | Gestion du site > Équipe > Ajouter un membre |
| Logos des partenaires | Gestion du site > Partenaires officiels > Ajouter un partenaire |
| Premiers articles | Articles > Ajouter un article |
| Téléphone, e-mail, réseaux sociaux | Gestion du site > Paramètres généraux |

## 2. Où modifier chaque élément

Toutes les rubriques sont dans **Gestion du site**. Chaque page a un bouton **Enregistrer les modifications** en bas de l'écran et un bouton **Voir le site**. Les images se choisissent dans la médiathèque WordPress (bouton **Choisir une image**).

| Élément du site | Rubrique | Ce qu'on peut modifier |
| --- | --- | --- |
| Menu du haut (ordinateur et mobile) | Apparence > Menus | Ajouter, renommer, supprimer, réordonner par glisser-déposer, sous-menus (glisser un élément sous un autre). Chaque élément a deux cases « Masquer sur ordinateur » et « Masquer sur mobile ». |
| Bannière principale | Bannière principale | Image de fond et cadrage (point de la photo toujours visible), zoom à l'ouverture, surtitre, étiquette rouge, titre sur deux lignes, texte, bouton principal et bouton secondaire (texte + lien). |
| Bandeau rouge de chiffres | Bannière principale > Bandeau de chiffres | Afficher ou non, ajouter / supprimer / réordonner les chiffres. |
| Le Club | Le Club | Surtitre, titre, accroche, texte (éditeur), grande et petite photo, étiquette « Rouge & fier », sections sportives (football, basketball…). |
| Publicités | Publicités | Ajouter autant de publicités que voulu : image, nom de l'annonceur, lien, ouverture dans un nouvel onglet, statut actif / inactif, ordre. Plusieurs publicités actives défilent au même emplacement. |
| Encart sans publicité | Textes des sections > Encart publicitaire | Offre « Votre publicité ici » et son bouton, délai de rotation, affichage de l'encart. |
| Objectifs du club | Objectifs du club | Surtitre, titre, texte, image de fond facultative, cartes d'objectifs (pictogramme, titre, texte ; ajout, suppression, glisser-déposer), 1 à 3 photos de bannière en fondu, légende et bouton facultatif. |
| Équipe | Équipe | Pour chaque membre : photo, nom complet, fonction, groupe (staff, gardien, défenseur, milieu, attaquant, autre), numéro de maillot, description, statut actif / inactif, ordre (glisser-déposer dans la liste ou champ « Ordre »). |
| Titre de la section Équipe | Textes des sections > Section Équipe | Surtitre, titre, texte, boutons de filtre, message quand l'équipe est vide. |
| Actualités | Actualités | Catégorie affichée sur l'accueil, nombre d'articles, éléments des cartes (image, catégorie, date, extrait), textes de la section. Les articles eux-mêmes se créent dans **Articles > Ajouter un article** (titre, texte, extrait, image mise en avant, catégorie). |
| Partenaires officiels | Partenaires officiels | Pour chaque partenaire : logo, nom, site web (sans lien, le logo n'est pas cliquable), statut, ordre. |
| Bandeau Partenaires | Textes des sections > Section Partenaires | Image de fond, surtitre, titre, texte, bouton, avantages (01, 02, 03…), titre au-dessus des logos, bouton sous les logos. |
| Contact et formulaire | Formulaires et WhatsApp | Numéro WhatsApp, message d'introduction, bouton flottant, textes de la section, titre et note du formulaire, champs (intitulé, type, obligatoire, longueur minimale, choix des listes), première ligne du message, libellé du bouton, message après envoi. |
| Logo, nom, coordonnées, réseaux, pied de page | Paramètres généraux | Logo, nom court et complet, devise, description Google, adresse, téléphone, e-mail, Facebook, Instagram, YouTube, TikTok, titres des colonnes, copyright, crédits photo. |
| Liens du pied de page | Apparence > Menus | Menus placés aux emplacements « Pied de page, colonne 1 » et « colonne 2 ». |

Chaque section de l'accueil peut être masquée par sa case « Afficher la section ».

### Types de liens des boutons

Chaque bouton propose deux types :

- **Ouvrir WhatsApp avec un message prérempli** : ouvre `https://wa.me/NUMÉRO?text=MESSAGE`. Tant que le numéro WhatsApp n'est pas renseigné, ces boutons mènent au formulaire de contact.
- **Aller vers une adresse** : une page (`https://…`), un article, ou une section de l'accueil (`#club`, `#projet`, `#equipe`, `#actualites`, `#partenaires`, `#contact`).

Un bouton sans texte n'est pas affiché.

### Formulaire de contact vers WhatsApp

1. Le navigateur vérifie les champs obligatoires, l'e-mail, le téléphone et les longueurs minimales.
2. WordPress revérifie tout côté serveur (jeton de sécurité, champ anti-robot, choix des listes, longueurs).
3. Le message est construit (« Nom : … », « Sujet : … »), encodé pour l'adresse, et WhatsApp (application ou WhatsApp Web) s'ouvre avec le message prérempli.
4. Le visiteur appuie lui-même sur **Envoyer** dans WhatsApp. Le site n'envoie et n'enregistre rien.

Le formulaire n'est affiché qu'une fois le numéro WhatsApp renseigné. Un lien `?sujet=Partenariat#contact` présélectionne le sujet.

### Une section ne s'affiche pas

Connecté en administrateur, ouvrez la page d'accueil : un encadré orange (visible seulement par vous) indique la cause.

- **Membres en brouillon ou inactifs** : seuls les membres « Publié » et cochés « Actif » apparaissent. Dans **Gestion du site > Équipe**, ouvrez le membre et cliquez sur **Publier** (ou **Mettre à jour**).
- **Section décochée** : **Gestion du site > Textes des sections**, case « Afficher la section ».
- **Extension désactivée** : **Extensions > Extensions installées**, activer « OC Lisala – Contenus du club ».
- **Ancienne page en cache** : si le site utilise une extension de cache (LiteSpeed Cache, WP Rocket, cache de l'hébergeur…), videz le cache après chaque modification.

## 3. Images conseillées

| Emplacement | Format |
| --- | --- |
| Bannière principale | Horizontal, 1920 × 1080 px minimum |
| Le Club | Grande photo 900 × 1100 px (4:5), petite photo 600 × 600 px |
| Bannière des objectifs | 1600 × 640 px (5:2) |
| Bandeau Partenaires | Horizontal, 1600 × 900 px |
| Membres de l'équipe | Portrait 600 × 750 px (4:5) |
| Articles (image mise en avant) | 1200 × 750 px (16:10) |
| Publicités | Bannière 1200 × 250 px |
| Logos partenaires | PNG ou SVG transparent, environ 400 × 200 px |

WordPress crée automatiquement les tailles réduites ; les images sont chargées à la demande (lazy loading). Une image absente laisse place au fond vert « terrain » d'origine sans casser la mise en page.

## 4. Contenu du thème

```
oc-lisala/
├── style.css, functions.php, screenshot.png
├── header.php, footer.php, front-page.php
├── index.php, page.php, single.php, archive.php, category.php, search.php, 404.php
├── comments.php, searchform.php
├── template-parts/           sections de l'accueil, carte d'article, bandeau de titre
├── inc/                      réglages, formulaire WhatsApp, menus, SEO, administration
│   └── plugins/oc-lisala-core.zip   extension installable en un clic
└── assets/css, assets/js, assets/images
```

## 5. Notes techniques

- Sécurité : réglages enregistrés par l'API Settings de WordPress avec nettoyage de chaque champ, jetons (nonces) sur les formulaires d'administration, le formulaire de contact, le glisser-déposer et l'activation rapide ; sorties échappées ; droits vérifiés (`edit_theme_options` pour les réglages, `edit_posts` pour l'équipe, les publicités et les partenaires).
- Accessibilité : lien d'évitement, navigation au clavier (menu, sous-menus, filtres), textes alternatifs repris de la médiathèque ou du nom du membre / partenaire, respect de « réduire les animations ».
- SEO : titre géré par WordPress, description, Open Graph et données structurées `SportsOrganization` sur l'accueil (désactivés automatiquement si Yoast, Rank Math, SEOPress ou AIOSEO est actif).
- Polices : Teko et Inter chargées depuis Google Fonts.
