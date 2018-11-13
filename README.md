# Previ - Gestion de maintenance préventive et reglementaire

Previ est un logiciel libre de droit, destiné à la gestion des visites préventives et réglementaires.
Ce logiciel vient en renfort d'autre logiciels de GMAO, en permantant l'informatisation des passations préventives et leurs archivage.
Prévue pour les organisations prévoyant de la maintenance, l'outil à été pensé pour être le plus générique possible.

Previ se découpe en deux applications :

Previ-ADMIN :
* Gestion des utilisateurs et des profils utilisateurs
* Gestion de l'arborescence des Equipements
* Création de Visites, de Fiches et d'opérations
* Visualisation des visites terminées
* Téléchargement des fiches pour archivage dans un logiciel de GMAO. 

Previ-PASSATION :
* Passation de nouvelle visiste.
* Tablette & smartphone friendly

## Histoire

Previ est né d'un projet universitaire réalisé dans le cadre de notre Licence 3 PRO Informatique à l'université de franche-comté.

@author BLEU Raphaël
@author BERNET Florent
@author DELAVOUX THIBAULT

Le projet est acctuellement en cours de développement.

## Getting Started

Les instructions suivantes vont vous guider dans le téléchargement et l'installation du logiciel.
Pour tout question, contactez le support à l'adresse :
previ.contact@gmail.com

### Prerequisites

* Serveur compatible PHP / MYSQL
* Navigateur Web supportés : Chrome V 70.0.3538.77 / Firefox 63.0.1 / Vivaldi V2.1


### Installing

#### Base de donnée

Un sript de création de la base de donnée est fournie dans le dossier BDD du projet.
Executer le scipt sur votre serveur MySql. 

#### Applicattion

L'application est à déposer sur votre seveur. Elle est prête à l'emploi et sera accessible à l'adresse définie de votre serveur.
Au démarage, la Base de donnée contient un utilisateur administrateur pour permettant de créer les autres comptes utilisateurs.

id = SYS
mdp = MANAGER

Il est conseillé de supprimer cet utilisateur après la création de votre compte personnel Administrateur.
<!-- 

## Deployment

Add additional notes about how to deploy this on a live system

-->
## Built With

* bootstrap - Framework web
* git - Gestionnaire de version 

## Versioning

### Derniere Version stable
* V1.0 : application en cours de développement

### Version en développement

* V1.0 : application en cours de développement

### Version Antérieur : 

* Aucune version antérieur pour le moment

## Authors

* **DELAVOUX Thibault** - *Projet initial, prototyping, Programmation PHP* - [tdel](https://github.com/tdelavoux)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the LICENSE.md file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
