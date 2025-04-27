
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
- Base de données **MySQL**

## Installation

### Prérequis
- **Apache** : Assurez-vous d'avoir installé un serveur local comme [XAMPP](https://www.apachefriends.org/index.html)
- **PHP** : Le projet utilise PHP, donc vous devez également avoir PHP installé avec XAMPP ou MAMP.
- **MySQL** : Une base de données MySQL doit être configurée pour ce projet.

### Instructions d'installation en local

1. **Cloner le dépôt** sur votre machine locale :
   ```bash
   git clone [url-du-repo]
   ```

2. **Placer le dossier du projet** dans le répertoire `htdocs` pour XAMPP (ou dans le dossier correspondant pour MAMP).

3. **Lancer Apache et MySQL** via XAMPP .

4. **Créer la base de données** :
   - Ouvrir **phpMyAdmin** via `http://localhost/phpmyadmin/`.
   - Créer une base de données nommée `offre_db` (ou un autre nom de votre choix).
   - Importer le fichier SQL contenant les tables **entreprises** et **offres** dans cette base de données.

5. **Configurer la connexion à la base de données** :
   - Ouvrez le fichier de configuration pour la connexion à la base de données, généralement situé dans `config/config.php` ou un fichier similaire.
   - Mettez à jour les informations suivantes pour correspondre à votre environnement local :
     ```php
     $host = 'localhost';  // Ou l'adresse de votre serveur de base de données
     $dbname = 'offres_db'; // Nom de la base de données
     $username = 'root';    // Nom d'utilisateur par défaut de MySQL
     $password = 'root';        // Mot de passe, généralement vide par défaut sur XAMPP
     ```

6. **Accéder au projet via votre navigateur** :
   ```
   http://localhost/script/ script(ou votre nom de dossier)
   ```

### Installation des dépendances (si nécessaire)
Si des dépendances doivent être installées via Composer, assurez-vous d'avoir **Composer** installé sur votre machine. Pour récupérer les dépendances, exécutez la commande suivante dans le répertoire du projet :
   ```bash
   composer install
   ```
j'ai pas utiliser de dependances dans ce projet

## Fonctionnalités principales

- **Accueil statique** affichant un formulaire simple
- **Formulaire d'inscription personnalisé** pour chaque offre cliquée
- **Inscription et connexion** je ne l'ai pas configurer
- **Mentions légales** accessibles en bas de page
- **Menu burger** responsive fonctionnant en JavaScript
- **Wishlist** :
  - Possibilité d'ajouter / retirer des offres
  - **(Ajout non fonctionnel actuellement)**

## Organisation des fichiers

- `/script/public/partials/` : Fichiers partagés (header, footer, CSS, JavaScript)
- `/script/views/` : Gestion de la wishlist, les offres, les entreprises
- `/script/controllers/` : Gestion de la wishlist, les offres, les entreprises
- `/script/models/` : Gestion de la wishlist, les offres, les entreprises
- `index/` : Gestion de la wishlist, les offres, les entreprises et des autres focntionalités
- `/composer.json/` est vide
- `/.htaccess` est vide
- `/README.md/`

## Version en ligne

Vous pouvez accéder à la version en ligne du site à l'adresse suivante :
- [Lebonplan en ligne] https://kabore.alwaysdata.net/index.php?action=home

## Auteur

Emmanuel Kaboré  
Contact : [ekabore929@gmail.com](mailto:ekabore929@gmail.com)

---

**Remarque** : Ce projet est uniquement à but éducatif. Il ne collecte aucune donnée personnelle.  
**Note** : La fonctionnalité d'ajout à la wishlist n'est pas terminée car le projet n'est pas encore connecté à une base de données complète.
