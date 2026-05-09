# PixelVerse Studios - Système de Gestion de Personnages

## FantasyRealm Online

Application web de gestion et personnalisation de personnages pour le MMORPG FantasyRealm Online.

## Stack technique

- **Front-end** : HTML5, CSS3, Bootstrap 5, JavaScript
- **Back-end** : PHP 8+ (natif avec PDO)
- **Base de données relationnelle** : MySQL / MariaDB
- **Base de données NoSQL** : MongoDB (logs d'activité)
- **Déploiement** : Compatible avec tout hébergement PHP/MySQL

## Prérequis

- PHP >= 7.4 avec extensions `pdo_mysql` et `mongodb`
- MySQL / MariaDB
- MongoDB (optionnel, pour les logs)
- Composer

## Installation locale

### 1. Cloner le dépôt

```bash
git clone https://github.com/votre-compte/pixelverse-studios.git
cd pixelverse-studios
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer la base de données MySQL

Créer une base de données `pixelverse` puis importer le fichier SQL :

```bash
mysql -u root -p pixelverse < database/pixelverse.sql
```

Modifier `config/database.php` avec vos identifiants MySQL.

### 4. Configurer MongoDB (optionnel)

Assurez-vous que MongoDB est en cours d'exécution. Modifiez `config/mongodb.php` si nécessaire.

### 5. Configurer le serveur web

Pointer le document root vers le dossier `public/`.

Avec PHP built-in server :
```bash
cd public
php -S localhost:8015
```

### 6. Accéder à l'application

Ouvrez votre navigateur à l'adresse `http://localhost:8000`

## Identifiants de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@pixelverse.com | Admin@123 |
| Employé | employee@pixelverse.com | Employee@123 |
| Utilisateur | user@pixelverse.com | User@123 |

## Structure du projet

```
pixelverse-studios/
├── config/           # Configurations BDD et session
├── controllers/      # Contrôleurs MVC
├── models/           # Modèles d'accès aux données
├── views/            # Templates et vues
├── public/           # Point d'entrée et assets
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── database/         # Fichiers SQL
├── docs/             # Documentation et livrables
└── README.md
```

## Sécurité

- Hashage des mots de passe avec `password_hash()` (bcrypt)
- Protection CSRF sur tous les formulaires
- Requêtes préparées PDO contre les injections SQL
- Sessions sécurisées
- Validation des entrées utilisateurs
- Conformité RGPD (gestion des données personnelles)

## Branches Git

- `main` : branche de production
- `develop` : branche de développement
- `feature/*` : branches de fonctionnalités

## Système de visualisation des personnages (Avatar Builder)

L'application intègre un **moteur de rendu par couches superposées** permettant de visualiser un personnage en temps réel selon les traits sélectionnés.

### Principe de superposition (z-index)

Chaque élément du personnage est rendu dans une couche indépendante, positionnée en `absolute` dans un conteneur relatif de 200×280px. Les couches se superposent selon cet ordre :

| Couche | z-index | Type | Description |
|---|---|---|---|
| Fond | 0 | CSS | Dégradé bleu ciel |
| Corps | 10 | Image PNG | Silhouette de base (6 corpulences) |
| Peau | 15 | Div CSS | Overlay coloré (`background-color` + `opacity: 0.7`) |
| Cheveux | 30 | Image PNG | Forme au-dessus de la tête (7 couleurs) |
| Vêtements | 40 | Image PNG | Tunique colorée selon le type de personnage |
| Armure | 45 | Image PNG | Plastron métallique par-dessus les vêtements |
| Yeux | 50 | Image PNG | Paire d'yeux avec iris coloré |
| Nez | 55 | Div CSS | 6 formes géométriques (droit, aquilin, camus...) |
| Bouche | 56 | Div CSS | 4 formes (fine, moyenne, large, cœur) |
| Armes | 60 | Image PNG | Épée, hache, arc, dague ou bâton |
| Icône | 100 | Emoji | Représentation du type (⚔️🔮🏹...) |

### Adaptation à la corpulence

Le système utilise **`transform: scale()`** en CSS pour adapter les vêtements, armures et armes à la taille du corps choisi, évitant ainsi de générer des dizaines d'images :

| Corpulence | Scale appliqué |
|---|---|
| Maigre | ×0.72 |
| Élancé | ×0.82 |
| Athlétique | ×0.92 |
| Musclé | ×1.00 |
| Trapu | ×1.12 |
| Gros | ×1.25 |

Les éléments du visage (yeux, nez, bouche, cheveux) sont également décalés verticalement par classe CSS selon la corpulence pour rester alignés avec la tête.

### Color picker pour la peau

La couleur de peau n'utilise pas d'image mais un **`<input type="color">`** qui applique directement une couleur hexadécimale au div overlay. Cette valeur est stockée en base de données (ex: `#f5d0a9`).

### Remplacer les images placeholder

Les images générées automatiquement se trouvent dans `public/assets/images/avatar/`. Pour les remplacer par de vraies illustrations :

1. Conservez les **mêmes noms de fichiers** (ex: `body/muscle.png`, `clothes/guerrier.png`)
2. Respectez les dimensions **200×280px** avec fond transparent (PNG)
3. Dessinez à l'échelle **"Musclé"** (scale 1.0) — le CSS adapte les autres tailles
4. Videz le cache du navigateur (Ctrl+F5)

## Auteur

Projet réalisé dans le cadre de l'ECF TP Développeur Web et Web Mobile.
