# Documentation Technique - PixelVerse Studios

## 1. Reflexions technologiques initiales

Pour repondre au besoin de PixelVerse Studios, plusieurs pistes techniques ont ete comparees avant de retenir une architecture web simple, lisible et compatible avec un rendu 3D navigateur.

- **Framework PHP complet (Symfony / Laravel)** : solution robuste mais trop lourde pour un MVP d'ECF.
- **PHP structure sans framework** : solution retenue pour garder un projet lisible, deployable partout et simple a justifier.
- **Base relationnelle MySQL / MariaDB** : indispensable pour les comptes, personnages, avis, accessoires et demandes de contact.
- **Base NoSQL MongoDB** : utilisee pour la journalisation des actions sensibles et des operations metier.
- **Front-end HTML / CSS / Bootstrap / JavaScript natif** : choix suffisant pour une interface responsive et un createur dynamique sans surcouche inutile.
- **Three.js** : retenu pour l'assemblage et la visualisation 3D dans le navigateur.

Les explorations sur les assets et le pipeline de personnalisation ont ensuite conduit aux arbitrages suivants :

- `Quaternius` a ete teste pour une premiere piste 3D ;
- `Universal LPC` a ete teste pour une piste 2D / sprites ;
- une version 2D a base d'images superposees a ete envisagee ;
- `FREE Starter Pack - Sidekick Modular Characters` a ete retenu comme base finale ;
- `Fantasy Forest Environment - Free Sample` a ete ajoute pour renforcer l'ambiance des vues corps complet ;
- `Human Basic Motions FREE` a ete teste dans Unity pour la marche, mais l'export web n'a pas ete conserve a la livraison.

## 2. Configuration de l'environnement de travail

### Local

L'environnement local retenu repose sur :

- Apache 2.4 + PHP 7.4 ou superieur sous WAMP / XAMPP ;
- MySQL ou MariaDB ;
- MongoDB Community Edition ;
- VS Code avec extensions PHP et Git ;
- Git avec branches `main`, `develop` et `feature/*` ;
- un serveur local PHP possible via `php -S localhost:8017 -t public`.

### Production

En production, le projet prend en compte :

- un hebergement PHP / MySQL compatible ;
- la lecture de `DATABASE_URL` pour la base relationnelle ;
- la lecture de `MONGODB_URI` pour les logs ;
- la lecture de `PHP_TIMEZONE` pour harmoniser les jetons temporels ;
- l'absence de secrets dans le depot et dans les livrables.

## 3. Modele Conceptuel de Donnees

Le schema principal s'appuie sur les tables suivantes :

- `users`
- `characters`
- `accessories`
- `character_accessories`
- `reviews`
- `contact_requests`

Les relations principales sont les suivantes :

- un utilisateur peut creer plusieurs personnages ;
- un personnage peut posseder plusieurs accessoires via une table de liaison ;
- un personnage peut recevoir plusieurs avis ;
- une demande de contact est rattachee a un utilisateur existant.

Un export visuel du MCD accompagne cette documentation :

- `docs/diagramme_mcd.png`

## 4. Diagrammes UML

### Diagramme d'utilisation

Le diagramme d'utilisation met en scene les acteurs et leurs actions principales :

- le **visiteur** qui consulte l'accueil, la galerie publique et la page contact ;
- le **joueur** qui cree des personnages, les modifie, les duplique et depose des avis ;
- l'**employe** qui valide les personnages, les avis et les accessoires ;
- l'**administrateur** qui gere les comptes employe et consulte les logs.

Un export visuel du diagramme accompagne cette documentation :

- `docs/diagramme_utilisation.png`

### Diagramme de sequence

Le diagramme de sequence retenu illustre le parcours de creation et de partage d'un personnage :

1. le joueur ouvre le createur ;
2. l'interface envoie les choix et verifie les champs obligatoires ;
3. le personnage est enregistre en base avec le statut `pending` ;
4. l'employe valide ou rejette la creation ;
5. le partage public devient disponible apres approbation.

Un export visuel du diagramme accompagne cette documentation :

- `docs/diagramme_sequence.png`

## 5. Architecture applicative

Le projet suit une organisation MVC legere :

- **Modele** : acces PDO et persistence metier ;
- **Vue** : gabarits PHP, composants communs et formulaires ;
- **Controleur** : orchestration des routes et des droits ;
- **Front dynamique** : modules JavaScript dedies au createur et au viewer.

Les points d'entree principaux sont les suivants :

- `public/index.php`
- les controleurs `AuthController`, `UserController`, `CharacterController`, `EmployeeController`, `AdminController`
- les modules `synty-character-builder.js` et `synty-character-viewer.js`

Le retour precedent sur la separation entre structure HTML et logique dynamique a ete pris en compte :

- les vues PHP gardent un role de structure et d'hydratation initiale ;
- la logique interactive repose sur des modules JavaScript dedies ;
- l'affichage 3D n'est pas gere dans les vues, mais dans un viewer front autonome.

## 6. Regles metier principales

- tout joueur connecte peut creer un personnage depuis son espace personnel ;
- un personnage cree ou modifie repasse au statut `pending` tant qu'il n'est pas revalide ;
- seuls les personnages `approved` peuvent etre partages publiquement ;
- les avis restent invisibles tant qu'une validation employee n'a pas ete effectuee ;
- le formulaire de contact reste public, mais le pseudo est verifie ;
- le mot de passe oublie genere un token temporaire et un lien de renouvellement ;
- le viewer 3D final reste stable et identique entre creation, edition, detail et galerie.

## 7. Securite implemente

| Menace | Contre-mesure |
|--------|---------------|
| Injection SQL | Requetes preparees PDO |
| XSS | Echappement avec `htmlspecialchars()` |
| CSRF | Token de session verifie sur les actions sensibles |
| Vol de session | Controle des droits et regeneration de session |
| Fuites de donnees | Hashage des mots de passe avec `password_hash()` |
| Elevation de privileges | Verification des roles sur chaque espace protege |
| Reinitialisation non securisee | Token unique avec expiration temporelle |
| Incoherence horodatage | Fuseau horaire applicatif centralise |

## 8. Deploiement

La demarche de deploiement retenue suit les etapes principales suivantes :

1. deployer le dossier projet ;
2. importer `database/pixelverse.sql` ;
3. configurer la connexion SQL ;
4. configurer la connexion MongoDB si la journalisation NoSQL est active ;
5. verifier l'inscription, la connexion, le mot de passe oublie, la creation de personnage et la moderation ;
6. activer HTTPS et desactiver `display_errors` en production.

## 9. Explorations 3D et arbitrages finaux

Le projet a mobilise plusieurs essais de pipeline avatar avant de se stabiliser.

Constats principaux :

- `Quaternius` presentait des limites sur certaines tenues et sur la variete faciale ;
- `Universal LPC` et la version 2D simplifiaient la technique, mais reduisaient l'effet MMORPG recherche ;
- `Synty Sidekick` apportait le meilleur compromis entre lisibilite, modularite et rendu web ;
- le decor `Fantasy Forest Environment - Free Sample` renforcait l'ambiance sans alourdir les parcours ;
- `Human Basic Motions FREE` fonctionnait dans Unity, mais l'export web de la marche n'etait pas suffisamment fiable pour la livraison finale.

Le pipeline final retenu est donc :

- `Synty Sidekick` pour le createur et le viewer final ;
- `Three.js` pour l'assemblage web ;
- un decor foret sur les vues corps complet ;
- un rendu temps reel stable sans export web final de marche.
