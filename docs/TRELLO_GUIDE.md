# Guide - Creer son board Trello de gestion de projet

## Etape 1 : Creer le board
1. Va sur https://trello.com
2. Cree un compte (gratuit) avec ton mail ou GitHub
3. Clique sur **"Create new board"**
4. Nomme-le : `PixelVerse Studios - Gestion de Projet`
5. Rend-le **PUBLIC** (obligatoire pour le partager a l'ecole)

## Etape 2 : Creer les listes (colonnes)

Cree ces 5 listes dans l'ordre :

1. **Backlog** (idees et taches futures)
2. **A faire** (taches du sprint en cours)
3. **En cours** (taches en developpement)
4. **En test** (taches a tester avant validation)
5. **Termine** (taches finies et mergees)

## Etape 3 : Creer les cartes

Copie-colle chaque carte dans la bonne liste.

### Dans "Termine" :

| Titre | Description |
|-------|-------------|
| **Sprint 0 : Analyse et conception** | Lecture CDC, architecture technique, MCD, UML, charte graphique, wireframes |
| **Sprint 1 : Mise en place technique** | Structure MVC, BDD MySQL, routeur PHP, sessions, securite de base |
| **Sprint 2 : Authentification et roles** | Inscription, connexion, roles (user/employe/admin), fixtures SQL, securisation routes |
| **Sprint 3 : Gestion des personnages** | CRUD personnages, filtrage, pagination, detail avec avatar builder |
| **Sprint 4 : Avatar builder** | Superposition couches z-index, adaptation corpulence (scale), color picker peau, positionnement visage |
| **Sprint 5 : Inventaire et accessoires** | Gestion inventaire, equipement armes/armures, calcul bonus stats |
| **Sprint 6 : Espace administrateur** | Dashboard stats, moderation comptes, gestion droits, consultation logs MongoDB |
| **Sprint 7 : Front-end et responsive** | Integration Bootstrap 5, responsive mobile/tablette/desktop, UX polishing |
| **Sprint 8 : Documentation et deploiement** | README, docs techniques, charte graphique, manuel utilisateur, Git branches, Railway deploy |

### Dans "En test" :

| Titre | Description |
|-------|-------------|
| **Tests finaux et validations** | Tests login/logout, creation personnage, avatar builder, permissions admin/employe, deploiement Railway |

### Dans "A faire" :

| Titre | Description |
|-------|-------------|
| **Livraison ECF** | Deposer la copie a rendre, liens GitHub + Railway + Trello |

## Etape 4 : Ajouter les labels (couleurs)

Cree ces labels pour organiser les cartes :

- 🟥 **Rouge** = Urgent / Bloquant
- 🟨 **Jaune** = Front-end
- 🟦 **Bleu** = Back-end
- 🟩 **Vert** = BDD / MongoDB
- 🟪 **Violet** = Documentation / Livrables

**Application des labels :**
- Sprint 0, 8 → 🟪 Violet
- Sprint 2, 3, 5, 6 → 🟦 Bleu
- Sprint 4, 7 → 🟨 Jaune
- Sprint 1 → 🟩 Vert
- Livraison ECF → 🟥 Rouge

## Etape 5 : Ajouter les dates

Clique sur chaque carte "Terminee" → **Dates** → mets la date de fin approximative (ex: 05/05/2026 pour le Sprint 0, 07/05/2026 pour le Sprint 8).

Cela montre que tu as planifie et suivi un calendrier.

## Etape 6 : Partager le lien

1. En haut a droite du board, clique sur **"Share"**
2. Copie le lien public du board
3. Colle ce lien dans ta copie a rendre (Word/Excel) a la ligne **"Logiciel de gestion de projet"**

---

**Exemple de lien Trello public :**
```
https://trello.com/b/XXXXXXXX/pixelverse-studios-gestion-de-projet
```

## Conseil rapide

Tu peux aussi glisser-deposer les cartes d'une liste a l'autre pour simuler ton avancement. Le jury aime voir que les cartes ont bouge durant le projet (du "A faire" vers "Termine").
