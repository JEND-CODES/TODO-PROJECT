# TODO-PROJECT

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4ff59ff646f54b828ff943c19a245dbd)](https://www.codacy.com/gh/JEND-CODES/TODO-PROJECT/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JEND-CODES/TODO-PROJECT&amp;utm_campaign=Badge_Grade)

[![SymfonyInsight](https://insight.symfony.com/projects/a2b7ac96-199d-49c1-a697-070378224166/big.svg)](https://insight.symfony.com/projects/a2b7ac96-199d-49c1-a697-070378224166)

## PRÉREQUIS

``` bash
* PHP >=7.2.5
* Composer v2.0.11
* MySql v5.7
* Apache v2.4.39
* npm v6.13.4
* yarn v1.22.5
* XDebug v3.0.4
* Blackfire
```

## INSTRUCTIONS D'INSTALLATION
``` bash
* CLONEZ LE PROJET : https://github.com/JEND-CODES/TODO-PROJECT

* INSTALLEZ LES DÉPENDANCES AVEC COMPOSER : composer install

* INSTALLEZ LES DÉPENDANCES CSS & JS : npm install

* ACTUALISEZ LE DOSSIER DES DÉPENDANCES : npm run dev

* CRÉEZ LA BASE DE DONNÉES (FICHIER .ENV) : doctrine:database:create

* GÉNÉREZ LES FIXTURES : doctrine:fixtures:load

* LANCEZ VOTRE SERVEUR ET CONNECTEZ-VOUS À L APPLICATION
```

## DOCUMENTATION

• Comment [participer au projet](https://github.com/JEND-CODES/TODO-PROJECT/blob/main/documentation/Participation.md)

• Implémentation de [l'authentification](https://github.com/JEND-CODES/TODO-PROJECT/tree/main/documentation)

• Rapport d'audit de qualité de code et de [performances](https://github.com/JEND-CODES/TODO-PROJECT/tree/main/documentation)

## DÉMO => http://todo.planetcode.fr

## HOMEPAGE

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/public/captures/Capcha_project_P8.JPG)

## DIAGRAMMES UML

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/P8%20Op%C3%A9rations%20autoris%C3%A9es%20par%20r%C3%B4les.JPG)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/Cas_Gestion_T%C3%A2ches_User_P8_V2.png)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/Cas_Gestion_T%C3%A2ches_Admin_P8_V2.png)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/S%C3%A9quence_Gestion_Utilisateurs_P8_V3.png)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/Diagramme_de_Classes_P8_V1.png)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/Mod%C3%A8le_de_donn%C3%A9es_P8_V1.png)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/documentation/diagrammes/Concepteur_BDD_Todolist_v2.png)

## CODE COVERAGE

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/public/captures/P8%20PhpUnit%20Tests%20results.png)

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/assets/images/Coverage_results_P8_V4.JPG)

## DEBUG ROUTER

![TODO-PROJECT](https://raw.githubusercontent.com/JEND-CODES/TODO-PROJECT/main/public/captures/P8%20Debug%20Router.png)

## RÉDUCTION DETTE TECHNIQUE

• Pagination pour l'affichage des tâches et du dashboard utilisateurs

• Utilisation de Webpack Encore pour améliorer le chargement des dépendances CSS & JS

• Système de cookies "remember_me" (security.yaml)