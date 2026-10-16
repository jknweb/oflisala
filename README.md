# OC Lisala · Site officiel

Site vitrine de l'Olympique Club de Lisala (Lisala, Mongala, RD Congo).
HTML5, CSS3 et JavaScript natif, sans framework ni outil de build : ouvrir `index.html` dans un navigateur suffit.

## Fichiers

```
index.html        Toutes les sections : #accueil, #club, #projet, #equipe, #actualites, #partenaires, #contact
style.css         Styles (couleurs et polices en variables dans :root)
script.js         Configuration du site + interactions
images/logo/      Logo officiel du club (SVG) et icônes
```

## À compléter

| Quoi | Où |
| --- | --- |
| Téléphone, e-mail, vrai numéro WhatsApp (provisoire : 243000000000), réseaux sociaux | `SITE_CONFIG` en haut de `script.js` (le bouton WhatsApp apparaît dès que le numéro est renseigné) |
| Joueurs, entraîneur, staff (nom, numéro, photo) | `TEAM` dans `script.js` |
| Histoire, date de fondation, stade | Section `#club` de `index.html` |
| Articles | Cartes `.news-card` et `<template id="article-N">` dans `index.html` |
| Logos des partenaires | Remplacer les `.partner-slot` dans `index.html` |

## Photos

Les photos sont des illustrations libres de droits chargées depuis [Pexels](https://www.pexels.com/license/) (licence Pexels : usage gratuit, attribution facultative).
Les personnes photographiées ne sont pas des joueurs du club. Si une image ne charge pas, un fond « terrain » vert s'affiche à la place.
Pour mettre les photos du club : remplacer l'attribut `src` (et `srcset` pour la bannière) par un fichier local, par exemple `images/equipe.jpg` (JPG ou WebP, moins de 250 Ko).

| Emplacement | Photo Pexels |
| --- | --- |
| Bannière d'accueil (diaporama) | `images/hero/joueurs-1.jpg`, `images/hero/joueurs-2.jpg` (images fournies par le club) |
| Le Club | [38242318](https://www.pexels.com/photo/38242318/), [38404136](https://www.pexels.com/photo/38404136/) |
| Projet sportif (diaporama) | [36826363](https://www.pexels.com/photo/36826363/), [38404129](https://www.pexels.com/photo/38404129/), [28735311](https://www.pexels.com/photo/28735311/) |
| Actualités | [32941171](https://www.pexels.com/photo/32941171/), [28735311](https://www.pexels.com/photo/28735311/), [27294512](https://www.pexels.com/photo/27294512/), [38404129](https://www.pexels.com/photo/38404129/) |
| Partenaires | [34927818](https://www.pexels.com/photo/34927818/) |

## Couleurs

Rouge principal `#A3060F` (dominant), noir `#1C1C1E`, blanc `#FFFFFF`, vert terrain `#2F7A43` en accent.
Polices : Teko (titres, chiffres et labels), Inter (texte).

## Vers WordPress

Chaque section est commentée avec son futur fichier de thème (`header.php`, `template-parts/section-*.php`, `footer.php`).
Les cartes répétées (`.player-card`, `.news-card`, `.axis-card`) correspondent aux futures boucles de contenu, `SITE_CONFIG` aux réglages du thème.
Le formulaire est en mode démonstration : à brancher sur Contact Form 7 ou WPForms.
