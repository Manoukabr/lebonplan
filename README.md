
# Lebonplan

Projet PHP réalisé dans le cadre d'un exercice scolaire.

## Description

Lebonplan est un site web d'annonces d'offres avec possibilité de :
- Consulter les offres disponibles (Accueil **statique**)
- Cliquer sur une offre pour accéder à un **formulaire personnalisé** adapté à l'offre choisie
- Ajouter des offres à une wishlist personnelle (**ajout non fonctionnel actuellement**)
- Gérer son compte (inscription / connexion)
- Accéder aux mentions légales
- Naviguer sur toutes les pages via un **menu burger responsive** (réalisé en JavaScript)

## Technologies utilisées

- PHP
- HTML / CSS
- JavaScript (menu burger)
- Serveur local **XAMPP**

## Installation

1. Cloner le dépôt sur votre machine locale :
   ```bash
   git clone [url-du-repo]
   ```
2. Placer le dossier dans votre serveur local (`htdocs` pour XAMPP)
3. Lancer Apache via XAMPP
4. Accéder au projet via votre navigateur :
   ```
   http://localhost/script/
   ```

## Fonctionnalités principales

- **Accueil statique** affichant toutes les offres
- **Formulaire d'inscription personnalisé** pour chaque offre cliquée
- **Inscription et connexion** avec des champs correctement paramétrés
- **Mentions légales** accessibles depuis le menu
- **Menu burger** responsive fonctionnant en JavaScript
- **Wishlist** :
  - Possibilité d'ajouter / retirer des offres
  - **(Ajout non fonctionnel actuellement)**

## Organisation des fichiers

- `/script/public/partials/` : Fichiers partagés (header, footer, CSS, JavaScript)
- `/script/views/` : Gestion de la wishlist, les offres, les entreprises
- `/script/controllers/` : Gestion de la wishlist, les offres, les entreprises
- `/script/models/` : Gestion de la wishlist, les offres, les entreprises


## Auteur

Emmanuel Kaboré  
Contact : [ekabore929@gmail.com](mailto:ekabore929@gmail.com)

---

**Remarque** : Ce projet est uniquement à but éducatif. Il ne collecte aucune donnée personnelle.  
**Note** : La fonctionnalité d'ajout à la wishlist n'est pas terminée car le projet n'est pas encore connecté à une base de données.
