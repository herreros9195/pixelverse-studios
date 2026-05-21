# Documentation de Gestion de Projet - PixelVerse Studios

## Methodologie

Le projet a ete mene selon une logique Agile / Scrum adaptee a un travail individuel. La demarche retenue consistait a avancer par blocs fonctionnels courts, avec une verification reguliere des parcours principaux, des arbitrages techniques et des livrables.

Le suivi a ete centralise dans un board Trello reconstituable a partir du JSON de projet, qui fusionne :

- les cartes historiques de production ;
- les cartes de blocage et d'arbitrage ;
- les cartes de verification finale ;
- les cartes de livrables.

## Planification

### Sprint 0 - Analyse et conception

- lecture de l'enonce ;
- extraction des besoins fonctionnels ;
- creation du MCD et des diagrammes UML ;
- definition de la charte graphique et des wireframes.

### Sprint 1 - Mise en place technique

- structure MVC ;
- configuration de la base relationnelle et de MongoDB ;
- sessions et securite de base ;
- premiers ecrans publics.

### Sprint 2 - Authentification et roles

- inscription et connexion ;
- mot de passe oublie ;
- gestion des roles joueur / employe / administrateur ;
- premiers controles d'acces.

### Sprint 3 - Gestion des personnages

- creation de personnage ;
- edition, duplication, suppression ;
- detail public ;
- partage apres validation.

### Sprint 4 - Moderation et administration

- validation ou rejet des personnages ;
- validation des avis ;
- gestion des accessoires ;
- creation et gestion des comptes employe ;
- consultation des logs administratifs.

### Sprint 5 - Recherche sur les pipelines avatar

- test du pack Quaternius ;
- test de Universal LPC ;
- test d'une version 2D a base d'images superposees ;
- comparaison avec le flux Synty Sidekick.

### Sprint 6 - Viewer 3D et ambiance

- integration Three.js ;
- createur modulaire par etapes ;
- presets de classe et variantes d'equipement ;
- integration du decor `Fantasy Forest Environment - Free Sample`.

### Sprint 7 - Animation de marche

- import de `Human Basic Motions FREE` dans Unity ;
- tests de marche sur les bodies et les prefabs complets ;
- essais de bake et d'export pour le web ;
- retour a un viewer stable sans marche exportee.

### Sprint 8 - Stabilisation et livraison

- nettoyage du depot ;
- fusion du SQL en un fichier unique ;
- harmonisation du board Trello ;
- regeneration des visuels et des PDF ;
- verification finale des livrables.

### Date de cloture

Pour la restitution finale, les cartes de synthese `Sprint 0` a `Sprint 9` sont harmonisees avec une date de fin au `21 mai 2026`.

## Gestion des versions

```text
main        : branche stable
develop     : integration
feature/*   : une branche par fonctionnalite
```

Le flux de travail retenu est le suivant :

1. creation d'une branche `feature/nom` depuis `develop` ;
2. developpement et tests locaux ;
3. merge vers `develop` apres validation ;
4. merge vers `main` en fin de cycle.

## Livrables

| Livrable | Statut | Emplacement |
|----------|--------|-------------|
| Code source | OK | Depot Git |
| SQL unifie | OK | `database/pixelverse.sql` |
| README | OK | `README.md` |
| Charte graphique PDF | OK | `docs/charte_graphique.pdf` |
| Manuel d'utilisation PDF | OK | `docs/manuel_utilisation.pdf` |
| Wireframes et mockups | OK | `docs/maquettes/` |
| Diagrammes MCD / UML | OK | `docs/diagramme_mcd.png`, `docs/diagramme_utilisation.png`, `docs/diagramme_sequence.png` |
| Documentation technique | OK | `docs/documentation_technique.md` et `docs/documentation_technique.pdf` |
| Documentation gestion projet | OK | `docs/gestion_projet.md` et `docs/gestion_projet.pdf` |
| Copie a rendre | OK | `copie-a-rendre-pixeverse.md` et `copie-a-rendre-pixeverse.docx` |
| Application deployee | A completer | URL publique a renseigner |

## Bilan

### Points forts

- createur 3D modulaire stable ;
- separation plus nette entre structure PHP et logique JavaScript ;
- moderation et administration completes ;
- journalisation MongoDB exploitable ;
- livrables visuels et documentaires coherents.

### Axes d'amelioration

- pipeline d'export web anime a reprendre avec plus de temps ;
- extension du catalogue d'armes et d'accessoires ;
- industrialisation plus poussee du pont Unity / web si une version post-ECF etait poursuivie.
