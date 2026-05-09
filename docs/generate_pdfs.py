from fpdf import FPDF
import os

class PDF(FPDF):
    def header(self):
        self.set_font('Arial', 'B', 12)
        self.set_text_color(26, 26, 46)
        self.cell(0, 10, 'PixelVerse Studios - FantasyRealm Online', 0, 0, 'L')
        self.ln(6)
        self.set_draw_color(26, 26, 46)
        self.line(10, self.get_y(), 200, self.get_y())
        self.ln(10)

    def footer(self):
        self.set_y(-15)
        self.set_font('Arial', 'I', 8)
        self.set_text_color(128)
        self.cell(0, 10, f'Page {self.page_no()}', 0, 0, 'C')

    def chapter_title(self, title):
        self.set_font('Arial', 'B', 14)
        self.set_text_color(22, 33, 62)
        self.cell(0, 10, title, 0, 1, 'L')
        self.ln(2)

    def chapter_body(self, body):
        self.set_font('Arial', '', 11)
        self.set_text_color(0)
        self.multi_cell(0, 6, body)
        self.ln()

# 1. CHARTE GRAPHIQUE
pdf = PDF()
pdf.add_page()
pdf.set_auto_page_break(auto=True, margin=15)

pdf.set_font('Arial', 'B', 20)
pdf.cell(0, 15, 'Charte Graphique', 0, 1, 'C')
pdf.ln(5)

pdf.chapter_title('Palette de couleurs')
pdf.chapter_body("""
Couleur principale (fond sombre) : #1A1A2E (bleu nuit profond)
Couleur secondaire : #16213E (bleu marine)
Accent : #E94560 (rouge corail)
Texte principal : #FFFFFF (blanc)
Texte secondaire : #F8F9FA (gris très clair)
Succès : #198754 (vert Bootstrap)
Danger / Avertissement : #DC3545 (rouge Bootstrap)
Info : #0DCAF0 (cyan Bootstrap)
""")

pdf.chapter_title('Typographie')
pdf.chapter_body("""
Police principale : Arial / Helvetica (sans-serif)
Titres : 20px (h1), 16px (h2), 14px (h3), gras
Corps de texte : 11px, interligne 1.5
Police alternative : Georgia (serif) pour les citations ou extraits RP
""")

pdf.chapter_title('Maquettes Bureautiques')
pdf.chapter_body("""
1. Page d'accueil : Hero pleine largeur avec présentation + grille 3 colonnes pour les avantages
2. Page de connexion : Formulaire centré (max 500px), fond clair, card avec ombre
3. Espace utilisateur : Sidebar gauche (navigation) + grille de cards personnages
""")

pdf.chapter_title('Maquettes Mobiles')
pdf.chapter_body("""
1. Page d'accueil : Hero réduit, cards empilées en colonne unique
2. Menu hamburger pour la navigation principale
3. Formulaires en pleine largeur avec champs adaptés au tactile (min 44px)
""")

pdf.output('charte_graphique.pdf')
print("charte_graphique.pdf généré")

# 2. MANUEL D'UTILISATION
pdf = PDF()
pdf.add_page()
pdf.set_auto_page_break(auto=True, margin=15)
pdf.set_font('Arial', 'B', 20)
pdf.cell(0, 15, 'Manuel d\'utilisation', 0, 1, 'C')
pdf.ln(5)

pdf.chapter_title('Présentation')
pdf.chapter_body("""
Le Système de Gestion de Personnages de FantasyRealm Online permet aux joueurs de créer,
personnaliser et gérer leurs avatars de manière détaillée. L'application propose également
un espace de modération pour les employés et un panneau d'administration pour le suivi.
""")

pdf.chapter_title('Parcours Visiteur')
pdf.chapter_body("""
- Accéder à la page d'accueil pour découvrir le projet
- Consulter les personnages partagés via le menu
- Utiliser le formulaire de contact (vérification du pseudo obligatoire)
- Créer un compte via le lien "Créer un compte"
""")

pdf.chapter_title('Parcours Utilisateur')
pdf.chapter_body("""
Identifiants test : user@pixelverse.com / User@123

1. Se connecter avec son email et mot de passe
2. Accéder à "Mon Espace" pour voir ses personnages
3. Créer un personnage (nom unique, genre, traits du visage)
4. Attendre la validation par un employé pour le partager
5. Modifier les traits et ajouter des accessoires
6. Partager / arrêter le partage du personnage
7. Dupliquer ou supprimer un personnage
8. Déposer des avis sur les personnages publics
""")

pdf.chapter_title('Parcours Employé')
pdf.chapter_body("""
Identifiants test : employee@pixelverse.com / Employee@123

1. Se connecter et accéder à "Espace Employé"
2. Approuver ou rejeter les personnages en attente (motif obligatoire si rejet)
3. Approuver ou refuser les avis en attente
4. Ajouter de nouveaux accessoires à la bibliothèque
5. Désactiver des accessoires existants
6. Supprimer des personnages ou suspendre des comptes utilisateurs
""")

pdf.chapter_title('Parcours Administrateur')
pdf.chapter_body("""
Identifiants test : admin@pixelverse.com / Admin@123

1. Se connecter et accéder à "Espace Administrateur"
2. Créer des comptes employés
3. Consulter les logs d'activité (stockés dans MongoDB)
4. Gérer les comptes employés (mot de passe, suspension, suppression)
5. Réaliser toutes les opérations d'un employé
""")

pdf.output('manuel_utilisation.pdf')
print("manuel_utilisation.pdf généré")

# 3. DOCUMENTATION TECHNIQUE
pdf = PDF()
pdf.add_page()
pdf.set_auto_page_break(auto=True, margin=15)
pdf.set_font('Arial', 'B', 20)
pdf.cell(0, 15, 'Documentation Technique', 0, 1, 'C')
pdf.ln(5)

pdf.chapter_title('Réflexions technologiques')
pdf.chapter_body("""
Le choix d'une stack LAMP (Linux, Apache, MySQL, PHP) avec Bootstrap pour le front
permet un déploiement rapide et peu coûteux sur la plupart des hébergeurs.
PHP natif avec PDO assure une compatibilité maximale sans dépendances lourdes.
MongoDB est utilisé uniquement pour les logs afin de respecter la contrainte NoSQL
du cahier des charges et de bénéficier d'une flexibilité sur les données non structurées.
""")

pdf.chapter_title('Configuration de l\'environnement')
pdf.chapter_body("""
- Serveur web : Apache 2.4+ avec mod_rewrite activé
- PHP 7.4+ avec extensions pdo_mysql et mongodb
- MySQL 5.7+ ou MariaDB 10.3+
- MongoDB 4.4+ (optionnel pour les logs)
- Composer pour la gestion des dépendances PHP

Le document root doit pointer vers le dossier public/ pour sécuriser les fichiers sensibles.
""")

pdf.chapter_title('Modèle Conceptuel de Données (MCD)')
pdf.chapter_body("""
ENTITES et RELATIONS :

USER (id, email, pseudo, password_hash, role, status)
  -> CHARACTER (1,N) : un utilisateur possède 0 à N personnages
  -> REVIEW (1,N) : un utilisateur dépose 0 à N avis

CHARACTER (id, user_id, name, gender, traits..., status, shared)
  -> REVIEW (1,N) : un personnage reçoit 0 à N avis
  -> ACCESSORY (N,N) via CHARACTER_ACCESSORIES

ACCESSORY (id, name, type, description, image_url, status)

CONTACT_REQUEST (id, email, pseudo, message, sent_at)
""")

pdf.chapter_title('Diagramme de séquence - Création de personnage')
pdf.chapter_body("""
Utilisateur -> Front : Remplit le formulaire
Front -> AuthController : Vérifie token CSRF + authentification
AuthController -> CharacterModel : Insertion en base
CharacterModel -> MySQL : INSERT INTO characters
MySQL -> CharacterModel : OK (pending)
CharacterModel -> AuthController : OK
AuthController -> LogModel : Enregistre l'action
LogModel -> MongoDB : Insert log
AuthController -> Front : Redirection avec message succès
""")

pdf.chapter_title('Déploiement')
pdf.chapter_body("""
1. Préparer l'environnement serveur (PHP, MySQL, MongoDB)
2. Cloner le dépôt Git sur le serveur
3. Lancer composer install
4. Importer le fichier database/pixelverse.sql
5. Configurer les accès BDD dans config/database.php et config/mongodb.php
6. Pointer le vhost vers le dossier public/
7. Activer mod_rewrite pour les URLs propres
8. Vérifier les permissions en écriture sur les dossiers de logs si nécessaire
9. Tester les parcours avec les identifiants de test
""")

pdf.output('documentation_technique.pdf')
print("documentation_technique.pdf généré")

# 4. DOCUMENTATION GESTION DE PROJET
pdf = PDF()
pdf.add_page()
pdf.set_auto_page_break(auto=True, margin=15)
pdf.set_font('Arial', 'B', 20)
pdf.cell(0, 15, 'Documentation de Gestion de Projet', 0, 1, 'C')
pdf.ln(5)

pdf.chapter_title('Méthodologie')
pdf.chapter_body("""
Le projet a été mené selon une approche agile simplifiée, adaptée à un travail individuel
sur une durée indicative de 70 heures. Le développement s'est organisé en sprints courts
de 2 à 3 jours, chacun délivrant une fonctionnalité utilisable.
""")

pdf.chapter_title('Outil de gestion')
pdf.chapter_body("""
Outil recommandé : Trello ou Notion

Colonnes / États :
- Backlog : idées et fonctionnalités à venir
- À faire : tâches planifiées pour le sprint en cours
- En cours : tâches en développement actif
- Test : vérifications fonctionnelles et recettes
- Terminé : fonctionnalités livrées et validées

Exemples de tickets :
- [FRONT] Intégrer la page d'accueil responsive
- [BACK] Implémenter l'inscription avec validation CNIL
- [BDD] Créer le schéma SQL et les fixtures
- [SEC] Ajouter la protection CSRF sur les formulaires
""")

pdf.chapter_title('Planification des phases')
pdf.chapter_body("""
Phase 1 - Analyse & Conception (10h)
  - Étude du cahier des charges
  - Modélisation de la base de données (MCD)
  - Maquettage wireframes & mockups

Phase 2 - Configuration & Structure (5h)
  - Mise en place de l'environnement local
  - Architecture MVC
  - Configuration Git (branches main, develop, feature/*)

Phase 3 - Développement Back-end (25h)
  - Authentification & sécurité
  - CRUD personnages & accessoires
  - Espace employé & administrateur
  - Intégration MongoDB pour les logs

Phase 4 - Développement Front-end (15h)
  - Intégration Bootstrap & responsive
  - Formulaires et validation JS
  - Pages publiques & privées

Phase 5 - Tests & Déploiement (10h)
  - Tests fonctionnels des parcours
  - Déploiement sur l'hébergeur
  - Rédaction des livrables

Phase 6 - Documentation (5h)
  - README, manuel, documentation technique
  - Charte graphique & maquettes exportées
""")

pdf.chapter_title('Gestion des versions')
pdf.chapter_body("""
Stratégie de branching GitFlow simplifiée :

main        : code de production stable
develop     : intégration des fonctionnalités testées
feature/xxx : branches de développement par fonctionnalité

Workflow :
1. Créer une branche feature depuis develop
2. Développer et tester localement
3. Merger feature -> develop après validation
4. Une fois le sprint terminé, merger develop -> main
""")

pdf.output('gestion_projet.pdf')
print("gestion_projet.pdf généré")

print("\nTous les documents PDF ont été générés avec succès dans le dossier docs/")
