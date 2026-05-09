# Documentation Technique - PixelVerse Studios

## 1. Reflexions technologiques initiales

Pour repondre aux besoins du client PixelVerse Studios, plusieurs technologies ont ete envisagees :

- **Framework PHP (Symfony/Laravel)** : trop lourd pour un MVP, mais envisageable pour la scalabilite future.
- **PHP procedural structure** : choisi pour sa simplicite de deploiement sur hebergement mutualise et sa lisibilite pour un projet de formation.
- **Base relationnelle (MySQL)** : obligatoire pour la gestion transactionnelle des utilisateurs, personnages et inventaires.
- **Base NoSQL (MongoDB)** : utilisee pour les logs d'activite et l'historique des actions, permettant une structure flexible.
- **Front-end vanilla** : HTML5, CSS3, Bootstrap 5 et JS natif suffisent pour les besoins actuels sans complexite inutile de React/Vue.

## 2. Configuration de l'environnement de travail

### Local (developpement)
- **Serveur** : Apache 2.4 + PHP 8.1 (WAMP64)
- **BDD relationnelle** : MariaDB 10.10.2 via phpMyAdmin (port 3307)
- **BDD NoSQL** : MongoDB Community Edition (localhost:27017)
- **IDE** : VS Code avec extensions PHP Intelephense
- **Versionning** : Git avec branches `main`, `develop` et feature branches

### Production
- Hébergement PHP/MySQL cloud (Railway, Render, Alwaysdata)
- MongoDB Atlas si les logs sont actives

## 3. Modele Conceptuel de Donnees (MCD)

```
[USERS] 1--* [CHARACTERS] (cree)
[CHARACTERS] 1--* [INVENTORY_ITEMS] *--1 [ITEMS]
[USERS] 1--* [FAVORITES] *--1 [CHARACTERS]
[CHARACTERS] 1--* [CHARACTER_TRAITS]
```

Entites principales :
- **users** : stockage des comptes (pseudo, email, password_hash, role)
- **characters** : caracteristiques des personnages (nom, type, corpulence, couleur de peau, traits)
- **inventory_items** : liaison N-N entre personnages et items avec quantite
- **favorites** : liaison N-N pour les personnages favoris
- **character_traits** : historique des traits modifiables

## 4. Diagrammes UML

### Diagramme de cas d'utilisation

```
        +------------------+
        |   Visiteur       |
        +------------------+
               | Consulter personnages
               v
        +------------------+
        |   Utilisateur    |---> Creer un personnage
        +------------------+---> Personnaliser l'avatar
               |                Gerer ses favoris
               v
        +------------------+
        |   Employe        |---> Modifier les personnages
        +------------------+---> Consulter les statistiques
               |
               v
        +------------------+
        |  Administrateur  |---> Valider/suspendre comptes
        +------------------+---> Gerer les roles utilisateurs
                                 Acceder au tableau de bord
```

### Diagramme de sequence (Creation d'un personnage)

```
Utilisateur    Frontend         Backend (PHP)       MySQL
  |                |                  |                |
  |-- Formulaire ------------------->|                |
  |                |<-- Validation --|                |
  |-- Soumission ------------------->|                |
  |                |                  |-- INSERT char -|
  |                |                  |<-- OK ---------|
  |                |<-- Redirection --|                |
  |<-- Avatar genere|                  |                |
```

## 5. Architecture applicative

Le projet suit une architecture **MVC simplifiee** :

- **Modele** : requetes PDO directes dans les pages (pas de ORM pour garder la maitrise SQL)
- **Vue** : templates PHP inclus (header/footer) avec Bootstrap
- **Controleur** : pages PHP dans `/controllers/` qui traitent les requetes et incluent les vues

### Points d'entree
- `public/index.php` : routeur frontal base sur `?page=`
- `public/assets/js/avatar-builder.js` : rendu interactif de l'avatar

## 6. Securite implementee

| Menace | Contre-mesure |
|--------|---------------|
| Injection SQL | Requetes preparees PDO avec parametres nommes |
| XSS | Echappement systematique avec `htmlspecialchars()` |
| CSRF | Tokens generes par session et verifies sur chaque action POST/GET sensible |
| Vol de session | Cookies de session securises, regeneration possible |
| Fuites de donnees | Pas de stockage de donnees bancaires, hashage bcrypt des mots de passe |
| Elevation de privileges | Verification du role sur chaque page protegee |

## 7. Deploiement

### Etapes de deploiement

1. **Preparation du serveur**
   - Verifier la version PHP (>= 7.4)
   - Activer les extensions `pdo_mysql` et `mongodb` (optionnel)

2. **Transfert des fichiers**
   - Deployer l'ensemble du dossier `pixelverse-studios/` via FTP/SFTP ou Git

3. **Base de donnees**
   - Creer une base MySQL sur l'hebergeur
   - Importer `database/pixelverse.sql`
   - Adapter `config/database.php` avec les nouveaux identifiants (ou utiliser `DATABASE_URL`)

4. **Configuration**
   - Si MongoDB Atlas est utilise, mettre a jour `config/mongodb.php` avec `MONGODB_URI`
   - Configurer les sessions PHP si necessaire (`php.ini`)

5. **Tests**
   - Verifier la connexion (login/logout)
   - Tester la creation d'un personnage
   - Verifier l'avatar builder
   - Controler les permissions (acces admin/employe)

6. **Mise en production**
   - Activer HTTPS (certificat SSL)
   - Desactiver l'affichage des erreurs PHP (`display_errors = Off`)
   - Configurer les logs serveur
