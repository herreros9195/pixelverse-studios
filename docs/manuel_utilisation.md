# Manuel d'utilisation - PixelVerse Studios

Version : 2.0  
Date : Mai 2026

## 1. Presentation de l'application

PixelVerse Studios est une plateforme web dediee a la creation, la moderation et au partage de personnages 3D pour `FantasyRealm Online`. La plateforme permet de consulter les personnages publics, de creer de nouveaux profils, de gerer les validations et de centraliser les avis.

Quatre profils principaux coexistent :

- **Visiteur** : consultation de l'accueil, de la galerie publique et de la page contact ;
- **Joueur** : creation de personnages, edition, duplication, partage et depot d'avis ;
- **Employe** : validation des personnages, avis et accessoires ;
- **Administrateur** : moderation globale, gestion des comptes employe et consultation des logs.

## 2. Acces a la plateforme

L'acces se fait via l'URL locale ou deployee du projet. Le menu principal donne acces :

- a la page d'accueil ;
- a la galerie publique ;
- a la connexion ;
- a la page contact.

### Comptes de test

| Role | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@pixelverse.com | Admin@123 |
| Employe | employee@pixelverse.com | Employee@123 |
| Joueur | user@pixelverse.com | User@123 |

## 3. Parcours visiteur

Un visiteur non connecte peut :

1. consulter la page d'accueil et les visuels d'ambiance ;
2. parcourir les personnages valides et partages ;
3. ouvrir le detail public d'un personnage ;
4. utiliser la page contact ;
5. acceder a l'inscription ou a la connexion.

Le depot d'avis et la creation d'un personnage ne sont pas disponibles sans connexion.

## 4. Parcours joueur

Le parcours joueur suit en general les etapes suivantes :

1. creation d'un compte via la page d'inscription ;
2. connexion avec email et mot de passe ;
3. demande de reinitialisation du mot de passe si besoin ;
4. creation d'un personnage en quatre etapes ;
5. consultation du tableau de bord ;
6. partage du personnage apres validation.

Le createur de personnage comporte quatre etapes :

1. `Identite`
2. `Apparence`
3. `Classe`
4. `Recapitulatif`

Les options principales couvrent :

- nom ;
- sexe ;
- corps de base ;
- teinte de peau ;
- barbe ou masque ;
- oreilles ;
- sourcils ;
- nez ;
- cheveux ;
- couleur des cheveux ;
- couleur des yeux ;
- classe ;
- variante d'equipement.

L'espace joueur affiche notamment :

- la liste des personnages du compte ;
- le statut de validation ;
- l'acces a l'edition ;
- la duplication ;
- la suppression ;
- le partage des personnages approuves.

## 5. Parcours employe

Le role employe permet de :

1. approuver ou rejeter les personnages en attente ;
2. valider ou rejeter les avis deposés ;
3. ajouter ou desactiver des accessoires ;
4. suspendre ou supprimer un compte joueur si necessaire.

L'employe joue le role de moderation fonctionnelle intermediaire.

## 6. Parcours administrateur

Le role administrateur donne acces :

1. au tableau de bord d'administration ;
2. a la creation de comptes employe ;
3. a la gestion des comptes employe existants ;
4. a la consultation des logs de l'application ;
5. a la regeneration de mot de passe d'un employe.

L'administrateur conserve les droits les plus etendus sur la plateforme.

## 7. Regles de fonctionnement importantes

- seuls les personnages valides peuvent etre visibles publiquement ;
- un personnage modifie peut repasser par une validation ;
- les avis restent invisibles tant qu'ils ne sont pas approuves ;
- le mot de passe oublie genere un lien temporaire de renouvellement ;
- le formulaire de contact reste public mais verifie le pseudo ;
- la galerie publique n'affiche que les personnages marques comme partages.

## 8. Securite et donnees

La plateforme integre plusieurs mesures de base :

- hashage des mots de passe avec `password_hash()` ;
- tokens CSRF sur les actions sensibles ;
- requetes preparees contre les injections SQL ;
- echappement HTML contre les scripts injectes ;
- token de reinitialisation avec expiration ;
- journalisation NoSQL des actions administratives et metier.

Pour une demande de suppression de compte ou une question de donnees personnelles, le canal prevu reste la page contact.

## 9. Rappel de deploiement

Le projet repose sur PHP, MySQL / MariaDB, MongoDB et Three.js. La mise en ligne suit en general :

1. l'envoi du code sur un hebergement compatible ;
2. l'import de `database/pixelverse.sql` ;
3. la verification de la connexion SQL ;
4. la configuration de MongoDB si la journalisation NoSQL est active ;
5. le test de l'inscription, de la connexion, du mot de passe oublie et de la creation de personnage ;
6. la verification des parcours joueur, employe et administrateur.
