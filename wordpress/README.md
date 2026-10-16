# Site OC Lisala sous WordPress

Conversion du site HTML/CSS/JS de la racine du dépôt en thème WordPress administrable.

| Dossier / fichier | Contenu |
| --- | --- |
| `oc-lisala/` | Thème « OC Lisala » (sources). Documentation complète : [oc-lisala/README.md](oc-lisala/README.md) |
| `oc-lisala-core/` | Extension « OC Lisala – Contenus du club » : Équipe, Publicités, Partenaires |
| `dist/oc-lisala.zip` | **Thème prêt à installer** (Apparence > Thèmes > Ajouter > Téléverser). Contient aussi l'extension, installable en un clic depuis le tableau de bord. |
| `dist/oc-lisala-core.zip` | Extension seule (Extensions > Ajouter > Téléverser) |
| `build.sh` | Reconstruit les deux archives après une modification des sources |

## Correspondance site d'origine → WordPress

| Section d'origine | Gabarit | Administration |
| --- | --- | --- |
| En-tête, menu, menu mobile | `header.php` | Apparence > Menus |
| Accueil (bannière) + chiffres | `template-parts/sections/hero.php`, `stats.php` | Gestion du site > Bannière principale |
| Le Club + espace publicitaire | `sections/club.php`, `ads.php` | Le Club, Publicités, Textes des sections |
| Projet sportif (objectifs, diaporama) | `sections/objectifs.php` | Objectifs du club |
| Équipe (cartes, filtres) | `sections/equipe.php` | Équipe, Textes des sections |
| Actualités (cartes, modale) | `sections/actualites.php`, `single.php` | Actualités + Articles (la modale est remplacée par une vraie page d'article) |
| Partenaires | `sections/partenaires.php` | Partenaires officiels, Textes des sections |
| Contact (formulaire WhatsApp) | `sections/contact.php`, `inc/whatsapp-form.php` | Formulaires et WhatsApp, Paramètres généraux |
| Pied de page, WhatsApp flottant | `footer.php` | Paramètres généraux, Apparence > Menus |

Les contenus de démonstration du site d'origine (articles d'exemple, fiches « Nom à compléter », emplacements de logos vides, mentions « À compléter ») ne sont pas repris : ces zones se remplissent depuis le tableau de bord et restent discrètes tant qu'elles sont vides.
