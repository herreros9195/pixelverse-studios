# Guide de deploiement Railway - PixelVerse Studios

## 1. Prerequis

- depot GitHub public contenant le projet ;
- compte Railway ;
- base MySQL Railway ou service externe compatible ;
- URI MongoDB si la journalisation NoSQL reste active ;
- commande de demarrage configuree dans Railway.

## 2. Preparation du depot

Verifier avant publication :

- presence de `composer.json`
- presence de `database/pixelverse.sql`
- README a jour ;
- dossier `public/` exploitable comme document root.

## 3. Creation du projet Railway

1. creer un projet Railway depuis le depot GitHub ;
2. laisser Railway analyser le depot PHP ;
3. configurer un service HTTP pointant vers `public/` ;
4. renseigner la commande de demarrage directement dans les parametres du service.

Commande de demarrage conseillee :

```bash
php -S 0.0.0.0:$PORT -t public
```

## 4. Variables d'environnement

Variables minimales :

```text
DATABASE_URL=mysql://USER:PASSWORD@HOST:PORT/DBNAME
MONGODB_URI=mongodb://...
PHP_TIMEZONE=Europe/Paris
```

Le projet lit directement :

- `DATABASE_URL` dans `config/database.php`
- `MONGODB_URI` dans `config/mongodb.php`

## 5. Import SQL

Le depot conserve un seul script d'installation.

Import conseille :

```bash
mysql -u USER -p -h HOST -P PORT < database/pixelverse.sql
```

Le script cree la base `pixelverse`, les tables et les donnees de demonstration.

## 6. Verifications apres deploiement

Routes publiques a controler :

- `/index.php?action=home`
- `/index.php?action=contact`
- `/index.php?action=login`
- `/index.php?action=register`
- `/index.php?action=characters`

Parcours authentifies a controler avec les comptes de demonstration :

- creation de personnage ;
- moderation employe ;
- administration ;
- detail public et avis.

## 7. Diagnostic

En cas d'erreur 500, verifier :

- presence de `DATABASE_URL`
- accessibilite de MySQL
- accessibilite de MongoDB si les logs sont actives
- droits de lecture sur `public/assets/models/synty`
- droits de lecture sur `public/assets/models/environments`

## 8. Informations a renseigner dans la livraison

- URL finale Railway ;
- lien GitHub public ;
- lien Trello public.
