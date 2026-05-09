# Guide de Deploiement - Railway (Gratuit)

Ce guide explique comment deployer **PixelVerse Studios** sur **Railway**, une alternative gratuite et simple a Heroku.

> **Avantage de Railway :** 5$ de credit gratuit par mois (suffisant pour ce projet), pas de mise en veille, et interface tres simple.

---

## 1. Prerequis

- Un compte Railway : https://railway.app (inscription gratuite avec GitHub)
- Un compte GitHub : https://github.com
- Le projet PixelVerse Studios pret a etre pousse sur GitHub

---

## 2. Pousser le projet sur GitHub

Si ce n'est pas deja fait, creez un depot public sur GitHub et poussez le code :

```bash
# A la racine du projet pixelverse-studios
cd pixelverse-studios

# Creer le depot sur GitHub d'abord (via l'interface web), puis :
git remote add origin https://github.com/VOTRE_USER/pixelverse-studios.git
git branch -M main
git push -u origin main
git push -u origin develop
```

> **Le depot doit etre PUBLIC** pour que Railway puisse y acceder gratuitement.

---

## 3. Creer un projet sur Railway

1. Allez sur https://railway.app/dashboard
2. Cliquez sur **"New Project"**
3. Selectionnez **"Deploy from GitHub repo"**
4. Choisissez votre depot `pixelverse-studios`
5. Railway detecte automatiquement **PHP** grace au `composer.json`

---

## 4. Ajouter une base de donnees MySQL

1. Dans votre projet Railway, cliquez sur **"New"** → **"Database"** → **"Add MySQL"**
2. Railway cree automatiquement une base MySQL
3. Attendez quelques secondes que la base soit prete

---

## 5. Configurer les variables d'environnement

Railway cree automatiquement une variable `DATABASE_URL` pour MySQL. **Le projet est deja configure pour la lire** (`config/database.php`).

Verifiez les variables dans l'onglet **Variables** de votre service :

```
DATABASE_URL = mysql://... (generee automatiquement)
```

### Ajouter la variable MongoDB (optionnel)

Si vous utilisez MongoDB Atlas pour les logs :
1. Cliquez sur **"New Variable"**
2. Ajoutez :
   - Key : `MONGODB_URI`
   - Value : `mongodb+srv://votre_user:votre_password@cluster0.xxxxx.mongodb.net/pixelverse_logs?retryWrites=true&w=majority`

### Ajouter le timezone PHP

1. Cliquez sur **"New Variable"**
2. Ajoutez :
   - Key : `PHP_TIMEZONE`
   - Value : `Europe/Paris`

---

## 6. Importer les donnees SQL

Il faut importer le fichier `database/pixelverse.sql` dans la base Railway.

### Methode 1 : Via MySQL Workbench (recommande)

1. Dans Railway, cliquez sur votre service MySQL
2. Allez dans l'onglet **Connect**
3. Recuperez les infos de connexion (host, port, user, password, database)
4. Ouvrez **MySQL Workbench** ou **DBeaver**
5. Creez une nouvelle connexion avec ces identifiants
6. Executez le fichier `database/pixelverse.sql`

### Methode 2 : Via ligne de commande

```bash
# Recuperez l'URL de connexion dans Railway (onglet Connect)
mysql -u USER -p -h HOST -P PORT DBNAME < database/pixelverse.sql
```

---

## 7. Configurer MongoDB Atlas pour Railway (optionnel)

MongoDB Atlas est deja en cloud. Assurez-vous que l'IP de Railway est autorisee :

1. Allez sur https://cloud.mongodb.com
2. Dans **Network Access**, cliquez sur **Add IP Address**
3. Selectionnez **Allow Access from Anywhere** (`0.0.0.0/0`)
4. Cliquez sur **Confirm**

---

## 8. Deployer l'application

### 8.1. Lancer le deploiement

1. Dans Railway, cliquez sur votre service **PixelVerse Studios**
2. Allez dans l'onglet **Settings**
3. Verifiez que le **Root Directory** est bien `/` (racine)
4. Le `Procfile` contient : `web: vendor/bin/heroku-php-apache2 public/`
5. Cliquez sur **Deploy** si ce n'est pas deja fait automatiquement

### 8.2. Obtenir l'URL

1. Attendez que le deploiement soit termine (statut vert)
2. Dans l'onglet **Settings**, cliquez sur **Generate Domain**
3. Railway vous donne une URL du type :
   ```
   https://pixelverse-studios-production-1234.up.railway.app
   ```

---

## 9. Verifier le deploiement

Ouvrez l'URL dans votre navigateur et testez :
- La page d'accueil charge
- La connexion fonctionne (comptes de test)
- La creation de personnages fonctionne
- L'avatar builder s'affiche correctement
- Les logs MongoDB fonctionnent (si configures)

---

## 10. Logs et debug

Si vous voyez une erreur 500 :

1. Dans Railway, cliquez sur votre service
2. Allez dans l'onglet **Logs**
3. Cherchez les erreurs PHP ou MySQL

Problemes courants :
- **Base non importee** : reimportez `database/pixelverse.sql`
- **DATABASE_URL manquante** : verifiez dans Variables
- **Extension mongodb manquante** : Railway installe automatiquement les extensions PHP via `composer.json`

---

## 11. Mise a jour de l'application

Pour mettre a jour apres des modifications :

```bash
git add .
git commit -m "Mise a jour"
git push origin main
```

Railway detecte automatiquement le push et redeploie l'application.

---

## 12. Limites du tier gratuit

| Ressource | Limite |
|-----------|--------|
| Credit mensuel | 5$ |
| CPU/RAM | Partage |
| Bande passante | 100 Go/mois |

Pour un projet PHP + MySQL comme PixelVerse Studios, les 5$ mensuels sont largement suffisants.

---

## 13. Alternatives si Railway ne convient pas

| Plateforme | Avantage | Inconvenient |
|------------|----------|--------------|
| **Render** (render.com) | Vrai tier gratuit | Veille apres 15 min d'inactivite |
| **Fly.io** | Generosite gratuite | Plus technique |
| **Alwaysdata** | 100 Mo gratuit | Moins de fonctionnalites |

---

**Besoin d'aide ?** Consultez la documentation Railway : https://docs.railway.app/
