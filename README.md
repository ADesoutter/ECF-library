Le but de cet exercice est de maîtriser la création d'une base de données (BDD) qui sera utilisée dans une application web dynamique.


# ECF Part 1

## Cahier des charges

Vous devez créer une BDD qui implémente la structure et les données indiquées plus bas.

Attention : l'accès à la BDD doit être limité à un unique utilisateur ayant le minimum possible de privilèges.

Pour créer la BDD, vous avez le choix des armes : SQL vanila, PHPMyAdmin, Doctrine (Symfony), Eloquent (Laravel), etc.
Mais vous êtes vivement encouragé à utiliser Symfony.


1. avec framework PHP
  - un ou des fichiers PHP contenant le code de création de la BDD
  - un ou des fichiers PHP contenant le code de création des données indispensables
  - un ou des fichiers PHP contenant le code de création des données de test


Dans tous les cas, le fichier `README.md` doit indiquer la procédure à suivre pour :

- si nécessaire, installer les dépendances (avec composer par exemple)
- créer l'utilisateur de la BDD
- créer la BDD
- créer la structure de la BDD
- injecter les données indispensables
- injecter les données de test

Optionnellement, vous pouvez aussi fournir un script Bash qui réalise chacune de ces opérations.

## Prérequis

- MariaDB
- PHPMyAdmin

Si vous utilisez Symfony :

- PHP 7.x ou 8.x
- composer

## Structure de la BDD, données indispensables et données de test

### User

Attributs :

- id : clé primaire
- email : varchar 190
- roles : text
- password : varchar 190

Relations :

- aucune

Données indispensables :

| id | email             | roles          | password                                                     |
|----|-------------------|----------------|--------------------------------------------------------------|
| 1  | admin@example.com | ["ROLE_ADMIN"] | $2y$10$/H2ChUxriH.0Q33g3EUEx.S2s4j/rGJH2G88jK9nCP60GbUW8mi5K |

Note : le mot de passe haché correspond au mot de passe clair `123`.

Données de test : aucunes

### Livre

Attributs :

- id : clé primaire
- titre : varchar 190
- annee_edition : int, nullable
- nombre_pages : int
- code_isbn : varchar 190, nullable

Relations :

- auteur : many to one
- genres : many to many
- emprunts : one to many

Données indispensables :

| id | titre                       | annee_edition | nombre_pages | code_isbn     | auteur_id |
|----|-----------------------------|---------------|--------------|---------------|-----------|
| 1  | Lorem ipsum dolor sit amet  | 2010          | 100          | 9785786930024 | 1         |
| 2  | Consectetur adipiscing elit | 2011          | 150          | 9783817260935 | 2         |
| 3  | Mihi quidem Antiochum       | 2012          | 200          | 9782020493727 | 3         |
| 4  | Quem audis satis belle      | 2013          | 250          | 9794059561353 | 4         |

| livre_id | genre_id |
|----------|----------|
| 1        | 1        |
| 2        | 2        |
| 3        | 3        |
| 4        | 4        |

Données de test : 1000 livres dont les données sont générées aléatoirement.
N'oubliez pas créer également les relations.

### Auteur

Attributs :

- id : clé primaire
- nom : varchar 190
- prenom : varchar 190

Relations :

- livres : one to many

Données indispensables :

| id | nom            | prenom |
|----|----------------|--------|
| 1  | auteur inconnu |        |
| 2  | Cartier        | Hugues |
| 3  | Lambert        | Armand |
| 4  | Moitessier     | Thomas |

Données de test : 500 auteurs dont les données sont générées aléatoirement

### Genre

Attributs :

- id : clé primaire
- nom : varchar 190
- description : text, nullable

Relations :

- livres : many to many

Données indispensables :

| id | nom              | description |
|----|------------------|-------------|
| 1  | poésie           | NULL        |
| 2  | nouvelle         | NULL        |
| 3  | roman historique | NULL        |
| 4  | roman d'amour    | NULL        |
| 5  | roman d'aventure | NULL        |
| 6  | science-fiction  | NULL        |
| 7  | fantasy          | NULL        |
| 8  | biographie       | NULL        |
| 9  | conte            | NULL        |
| 10 | témoignage       | NULL        |
| 11 | théâtre          | NULL        |
| 12 | essai            | NULL        |
| 13 | journal intime   | NULL        |

Données de test : aucunes

### Emprunteur

Attributs :

- id : clé primaire
- nom : varchar 190
- prenom : varchar 190
- tel : varchar 190
- actif : boolean
- date_creation : datetime
- date_modification : datetime, nullable

Relations :

- emprunts : one to many
- user : one to one, unidirectionnel

Données indispensables :

| id | nom | prenom | email               | tel       | actif | date_creation     | date_modification |
|----|-----|--------|---------------------|-----------|-------|-------------------|-------------------|
| 1  | foo | foo    | foo.foo@example.com | 123456789 | true  | 20200101 10:00:00 | NULL              |
| 2  | bar | bar    | bar.bar@example.com | 123456789 | false | 20200201 11:00:00 | 20200501 12:00:00 |
| 3  | baz | baz    | baz.baz@example.com | 123456789 | true  | 20200301 12:00:00 | NULL              |

Données de test : 100 emprunteurs dont les données sont générées aléatoirement

### Emprunt

Attributs :

- id : clé primaire
- date_emprunt : datetime
- date_retour : datetime, nullable

Relations :

- emprunteur : many to one
- livre : many to one

Données indispensables :

| id | date_emprunt        | date_retour         | emprunteur_id | livre_id |
|----|---------------------|---------------------|---------------|----------|
| 1  | 2020-02-01 10:00:00 | 2020-03-01 10:00:00 | 1             | 1        |
| 2  | 2020-03-01 10:00:00 | 2020-04-01 10:00:00 | 2             | 2        |
| 3  | 2020-04-01 10:00:00 | NULL                | 3             | 3        |

Données de test : 200 emprunts dont les données sont générées aléatoirement



# ECF-Part 2

## Les requêtes


### Les utilisateurs


Requêtes de lecture :


- la liste complète de tous les utilisateurs (de la table `user`)

- les données de l'utilisateur dont l'id est `1`

- les données de l'utilisateur dont l'email est `foo.foo@example.com`

- les données des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_EMRUNTEUR`



### Les livres


Requêtes de lecture :


- la liste complète de tous les livres

- les données du livre dont l'id est `1`

- la liste des livres dont le titre contient le mot clé `lorem`

- la liste des livres dont l'id de l'auteur est `2`

- la liste des livres dont le genre contient le mot clé `roman`



Requêtes de création :


- ajouter un nouveau livre

- titre : Totum autem id externum

- année d'édition : 2020

- nombre de pages : 300

- code ISBN : 9790412882714

- auteur : Hugues Cartier (id `2`)

- genre : science-fiction (id `6`)



Requêtes de mise à jour :


- modifier le livre dont l'id est `2`

- titre : Aperiendum est igitur

- genre : roman d'aventure (id `5`)



Requêtes de suppression :



- supprimer le livre dont l'id est `123`



### Les emprunteurs


Requêtes de lecture :


- la liste complète des emprunteurs

- les données de l'emprunteur dont l'id est `3`

- les données de l'emprunteur qui est relié au user dont l'id est `3`

- la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`

- la liste des emprunteurs dont le téléphone contient le mot clé `1234`

- la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit)

- la liste des emprunteurs inactifs (c-à-d dont l'attribut `actif` est égal à `false`)



### Les emprunts


Requêtes de lecture :


- la liste des 10 derniers emprunts au niveau chronologique

- la liste des emprunts de l'emprunteur dont l'id est `2`

- la liste des emprunts du livre dont l'id est `3`

- la liste des emprunts qui ont été retournés avant le 01/01/2021

- la liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle)

- les données de l'emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés (c-à-d dont la date de retour est nulle)



Requêtes de création :


- ajouter un nouvel emprunt

- date d'emprunt : 01/12/2020 à 16h00

- date de retour : aucune date

- emprunteur : foo foo (id `1`)

- livre : Lorem ipsum dolor sit amet (id `1`)



Requêtes de mise à jour :


- modifier l'emprunt dont l'id est `3`

- date de retour : 01/05/2020 à 10h00



Requêtes de suppression :


- supprimer l'emprunt dont l'id est `42`
