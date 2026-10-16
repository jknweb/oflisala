# Olympique Club de Lisala · Site officiel (maquette)

Maquette HTML/CSS/JS du site de l'Olympique Club de Lisala (OC Lisala), basée sur la charte graphique V2 du club.
Aucune dépendance, aucun outil de build : ouvrir `index.html` dans un navigateur suffit.

## Structure

```
index.html                  Page unique, une <section> par rubrique
assets/
  css/style.css             Styles (variables de couleurs en tête de fichier)
  js/script.js              Configuration du site + interactions
  img/logo/                 Logo officiel (SVG) et icônes
  img/photos/               Visuels provisoires « PHOTO À REMPLACER »
```

## Ce qu'il faut compléter

| Quoi | Où |
| --- | --- |
| Adresse, téléphone, e-mail, numéro WhatsApp, liens des réseaux | `SITE_CONFIG` en haut de `assets/js/script.js` |
| Joueurs et staff (nom, photo, numéro) | `TEAM_SLOTS` dans `assets/js/script.js` |
| Photos | Remplacer les fichiers de `assets/img/photos/` (JPG ou WebP, < 250 Ko) et mettre à jour le `src` dans `index.html` |
| Textes marqués `[À compléter]`, `is-placeholder` ou `tag-todo` | `index.html` |
| Articles d'actualité | Cartes `.news-card` et `<template id="article-N">` dans `index.html` |

Le bouton « Repérer les contenus provisoires » du bandeau jaune surligne tout ce qui reste à remplir.
Supprimer le bloc `.demo-bar` au moment de la mise en ligne.

## Couleurs et polices

Toutes les couleurs sont des variables CSS dans `:root` (`assets/css/style.css`) :
rouge Lisala `#E30713`, rouge braise `#A3060F`, noir `#0A0A0A`, anthracite `#1C1C1E`, gris terrain `#EDEDED`.
Polices : Montserrat (titres), Barlow Condensed (labels), Barlow (texte).

## Vers WordPress

Chaque section porte un commentaire indiquant son futur fichier de thème (`header.php`, `template-parts/section-*.php`, `footer.php`…).
Les composants répétés (`.news-card`, `.person-card`, `.offer-card`, `.org-card`, `.feature-card`) correspondent aux futures boucles de contenu,
et `SITE_CONFIG` aux réglages du thème. Les formulaires sont en mode démonstration (aucun envoi) : à brancher sur Contact Form 7 ou WPForms.
