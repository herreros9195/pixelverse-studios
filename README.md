# PixelVerse Studios - Plateforme de creation de personnages FantasyRealm

**PixelVerse Studios** est une application web dediee a la creation, la moderation et au partage de personnages pour l'univers `FantasyRealm Online`. La plateforme permet de consulter les personnages publics, de creer de nouveaux profils, de gerer les validations, de publier les personnages approuves et de centraliser les avis.

## Stack technique

- **Front-end** : HTML5, CSS3, Bootstrap 5, JavaScript natif, Three.js
- **Back-end** : PHP 7.4+ avec PDO
- **Base de donnees relationnelle** : MySQL / MariaDB
- **Base de donnees NoSQL** : MongoDB pour la journalisation des actions
- **Assets 3D** : `FREE Starter Pack - Sidekick Modular Characters` de Synty Studios
- **Decor 3D** : `Fantasy Forest Environment - Free Sample`
- **Deploiement** : compatible avec les hebergeurs PHP classiques et Railway

## Prerequis

- un serveur web Apache ou Nginx avec PHP 7.4+ ;
- l'extension PHP `pdo_mysql` activee ;
- l'extension PHP `mongodb` activee si la journalisation NoSQL est utilisee ;
- MySQL ou MariaDB ;
- MongoDB ;
- Git pour le versionnement.

---

## 1. Installation en local

### 1.1. Recuperation du projet

Une installation locale type suit les etapes suivantes :

```bash
git clone https://github.com/herreros9195/pixelverse-studios.git
cd pixelverse-studios
```

### 1.2. Configuration SQL

1. Ouvrir **phpMyAdmin** ou un client SQL equivalent.
2. Importer le fichier `database/pixelverse.sql`.
3. Verifier que la base `pixelverse`, les tables et les donnees de demonstration ont bien ete chargees.

### 1.3. Configuration de la connexion PDO

La configuration relationnelle par defaut se trouve dans `config/database.php`.

Les variables d'environnement usuelles peuvent aussi etre definies ainsi :

```text
DATABASE_URL=mysql://USER:PASSWORD@HOST:PORT/DBNAME
```

### 1.4. Configuration MongoDB

La configuration NoSQL se trouve dans `config/mongodb.php`.

Variables possibles :

```text
MONGODB_URI=mongodb://localhost:27017
```

### 1.5. Fuseau horaire PHP

Le projet suppose un fuseau `Europe/Paris`. Une configuration locale classique ajoute donc :

```ini
date.timezone = Europe/Paris
```

Une variable applicative peut aussi etre definie :

```text
PHP_TIMEZONE=Europe/Paris
```

---

## 2. Lancement local

Le lancement local suit en general cette logique :

1. verifier la base SQL ;
2. verifier la connexion MongoDB si elle est activee ;
3. lancer le serveur PHP local ;
4. tester l'accueil, la connexion et la galerie publique.

Commande type :

```bash
php -S localhost:8017 -t public
```

Acces principal :

- [http://localhost:8017/index.php?action=home](http://localhost:8017/index.php?action=home)

---

## 3. Workflow Git

### 3.1. Branches

```text
main        : branche stable
develop     : branche d'integration
feature/*   : une branche par fonctionnalite
```

### 3.2. Cycle de travail type

```bash
git checkout develop
git pull origin develop
git checkout -b feature/nom-fonctionnalite

git add .
git commit -m "Ajout de la fonctionnalite"

git checkout develop
git merge feature/nom-fonctionnalite
git push origin develop
```

### 3.3. Premier push GitHub

```bash
git remote add origin https://github.com/herreros9195/pixelverse-studios.git
git branch -M main
git push -u origin main
git push -u origin develop
git push origin --all
```

---

## 4. Comptes de demonstration

| Role | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@pixelverse.com | Admin@123 |
| Employe | employee@pixelverse.com | Employee@123 |
| Joueur | user@pixelverse.com | User@123 |

---

## 5. Structure du projet

```text
pixelverse-studios/
|-- config/           # Configuration SQL, MongoDB et options avatar
|-- controllers/      # Controleurs MVC
|-- database/         # SQL principal du projet
|-- docs/             # Documentation de remise
|-- helpers/          # Services utilitaires
|-- models/           # Acces donnees et logique metier
|-- public/           # Point d'entree, assets et vues publiques
|-- views/            # Vues PHP
|-- README.md         # Documentation principale
```

---

## 6. Fonctionnalites principales

- page d'accueil publique, page contact, mentions legales, confidentialite et CGV ;
- authentification complete avec inscription, connexion, deconnexion, mot de passe oublie et reinitialisation ;
- espace joueur avec creation, edition, duplication, suppression et partage de personnages ;
- createur 3D modulaire en quatre etapes : identite, apparence, classe, recapitulatif ;
- galerie publique filtrable et fiche detail avec avis moderes ;
- espace employe pour la validation des personnages, des avis, des accessoires et des comptes ;
- espace administrateur pour la creation des comptes employe et la consultation des logs ;
- viewer 3D Synty sur les ecrans de creation, edition, galerie, detail et tableau de bord ;
- decor foret sur les apercus corps complet.

---

## 7. Choix techniques et arbitrages

Plusieurs pistes ont ete explorees avant la stabilisation finale :

- `Quaternius` : essais de tenues et d'assemblage, abandonnes a cause de bugs visuels et d'une personnalisation faciale trop limitee ;
- `Universal LPC` : piste 2D lisible, mais rendu trop eloigne d'un MMORPG heroic fantasy cible ;
- `version 2D superposee` : solution plus simple techniquement, non retenue pour privilegier une integration 3D complete avec Three.js et WebGL ;
- `Human Basic Motions FREE` : animation de marche validee dans Unity, mais export web non conserve en raison d'un resultat instable et peu fiable dans le navigateur.

Le perimetre rendu conserve donc :

- le createur 3D modulaire Synty ;
- le decor foret dans les apercus corps complet ;
- un rendu temps reel stable sans export web final de marche.

---

## 8. Separation PHP / HTML / JavaScript

Le retour precedent sur les vues serveur a ete pris en compte dans l'organisation du projet :

- les routes, l'authentification, les formulaires et l'hydratation des donnees restent portes par des vues PHP ;
- le comportement dynamique de l'interface est isole dans des modules JavaScript dedies ;
- le createur et le viewer 3D sont geres par `public/assets/js/synty-character-builder.js` et `public/assets/js/synty-character-viewer.js` ;
- les vues clientes jouent principalement le role de structure HTML et de transport des donnees vers le JavaScript via des attributs `data-*`.

Cette organisation conserve un MVC PHP lisible pour l'evaluation, tout en separant davantage le rendu structurel et la logique dynamique du front.

---

## 9. Securite

- hashage des mots de passe avec `password_hash()` ;
- requetes preparees PDO contre les injections SQL ;
- tokens CSRF sur les actions sensibles ;
- echappement HTML avec `htmlspecialchars()` ;
- controle de role sur les espaces proteges ;
- lien de reinitialisation de mot de passe avec token et expiration ;
- journalisation MongoDB des actions metier et administratives.

---

## 10. Documentation disponible

Le dossier `docs/` contient :

- `charte_graphique.pdf`
- `manuel_utilisation.pdf`
- `documentation_technique.md` et `documentation_technique.pdf`
- `gestion_projet.md` et `gestion_projet.pdf`
- `diagramme_mcd.png`
- `diagramme_utilisation.png`
- `diagramme_sequence.png`
- `maquettes/`
- `GUIDE_DEPLOIEMENT_RAILWAY.md`

---

## 11. Verification locale conseillee

- `php -l` sur les fichiers PHP modifies ;
- ouverture des routes publiques principales ;
- creation d'un personnage, validation employe puis partage ;
- verification du viewer 3D sur creation, detail et galerie ;
- test du mot de passe oublie et du renouvellement ;
- controle des comptes de demonstration.

---

## 12. Elements a renseigner hors depot

- URL GitHub publique
- URL de l'application deployee
- URL publique du board Trello

Les cartes de synthese `Sprint 0` a `Sprint 9` du JSON Trello de livraison sont harmonisees avec une date de fin au `21 mai 2026`.

## 13. Auteur

Projet realise dans le cadre d'une evaluation DWWM.
