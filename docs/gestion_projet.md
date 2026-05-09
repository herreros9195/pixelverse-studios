# Documentation de Gestion de Projet - PixelVerse Studios

## Methodologie

Ce projet a ete realise selon une approche inspiree de la methode **Agile / Scrum**, adaptee a un developpement individuel en formation.

## Planification

### Sprint 0 : Analyse et conception (1-2 jours)
- Lecture du cahier des charges et extraction des besoins fonctionnels
- Definition de l'architecture technique (stack PHP/MySQL/MongoDB)
- Creation du MCD et des diagrammes UML
- Choix de la charte graphique et creation des wireframes

### Sprint 1 : Mise en place technique (1 jour)
- Creation de la structure de dossiers
- Configuration de la base de donnees relationnelle (MySQL)
- Mise en place du routeur PHP et des templates
- Configuration des sessions et securite de base (CSRF, bcrypt)

### Sprint 2 : Authentification et roles (2 jours)
- Developpement de l'inscription et de la connexion
- Gestion des roles (Utilisateur, Employe, Administrateur)
- Securisation des routes et controle d'acces
- Creation des donnees de test (fixtures SQL)

### Sprint 3 : Gestion des personnages (3-4 jours)
- CRUD des personnages (creation, modification, suppression logique)
- Systeme de filtrage et pagination
- Page de detail avec avatar builder
- Visualisation en temps reel des traits

### Sprint 4 : Avatar builder et personnalisation (2-3 jours)
- Systeme de superposition par couches (z-index)
- Adaptation a la corpulence (transform: scale)
- Color picker pour la peau
- Positionnement dynamique des traits du visage

### Sprint 5 : Inventaire et objets (2 jours)
- Gestion de l'inventaire par personnage
- Equipement d'armes et armures
- Calcul des bonus statistiques

### Sprint 6 : Espace administrateur (2 jours)
- Tableau de bord statistique
- Moderation des comptes (valider, suspendre)
- Gestion des droits utilisateurs (promotion/retrogradation)

### Sprint 7 : Front-end et responsive (2 jours)
- Integration Bootstrap 5
- Affichage responsive mobile/tablette/desktop
- Polishing UX (alertes, loaders, etats vides)

### Sprint 8 : Documentation et deploiement (2 jours)
- Redaction du README et documentation technique
- Creation de la charte graphique et du manuel d'utilisation
- Preparation du depot Git avec branches main/develop
- Tests finaux et deploiement Railway

## Gestion des versions (Git)

```
main        : branche de production, stable
develop     : branche d'integration, tests en cours
feature/*   : une branche par fonctionnalite (ex: feature/avatar, feature/auth)
```

Flux de travail :
1. Creation d'une branche `feature/nom` depuis `develop`
2. Developpement et commits reguliers
3. Merge de `feature/nom` vers `develop` apres tests locaux
4. Merge de `develop` vers `main` une fois le sprint valide

## Livrables

| Livrable | Statut | Emplacement |
|----------|--------|-------------|
| Code source | ✅ | Depot GitHub |
| Fichiers SQL | ✅ | `/database/pixelverse.sql` |
| README.md | ✅ | `/README.md` |
| Charte graphique | ✅ | `/docs/charte_graphique.html` |
| Manuel d'utilisation | ✅ | `/docs/manuel_utilisation.html` |
| Documentation technique | ✅ | `/docs/documentation_technique.md` |
| Documentation gestion projet | ✅ | `/docs/gestion_projet.md` |
| Guide deploiement Railway | ✅ | `/docs/GUIDE_DEPLOIEMENT_RAILWAY.md` |
| Application deployee | ⏳ | A configurer par l'utilisateur |

## Bilan et axes d'amelioration

**Points forts :**
- Architecture claire et maintenable
- Securite renforcee (CSRF, PDO, XSS)
- Responsive design
- Separation des roles bien definie
- Systeme d'avatar innovant par couches

**Ameliorations futures :**
- Integration complete de MongoDB pour les logs temps reel
- API REST complete pour une future application mobile
- Systeme de notifications email (SendGrid/Mailgun)
- Upload d'images personnalisees pour les avatars
- Tests unitaires (PHPUnit)
- Multi-langue (i18n)
