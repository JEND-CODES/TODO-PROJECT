# COMMENT CONTRIBUER AU PROJET

## 1 • Clonez le projet

```text
git clone https://github.com/JEND-CODES/TODO-PROJECT
```

## 2 • Installez les dépendances avec Composer

```text
composer install
```

## 3 • Indiquez l'accès à votre base de données dans le fichier .env, puis créez-la

```text
php bin/console doctrine:database:create
```

## 4 • Générez les fixtures

```text
php bin/console doctrine:fixtures:load
```

## 5 • Apportez vos modifications au projet

• Consultez les derniers [diagrammes](https://github.com/JEND-CODES/TODO-PROJECT/tree/main/documentation/diagrammes) sur le fonctionnement de l'application

• Consultez la documentation technique sur [l'implémentation de l'authentification](https://github.com/JEND-CODES/TODO-PROJECT)

• Consultez le dernier [rapport de couverture](https://github.com/JEND-CODES/TODO-PROJECT/tree/main/tests-coverage) des tests unitaires et fonctionnels
 
• Consultez le [rapport d'audit](https://github.com/JEND-CODES/TODO-PROJECT) de qualité de code et de performance

## 6 • Réalisez des tests unitaires de vos modifications

```text
php bin/phpunit
```

## 7 • Créez éventuellement un dossier de couverture html des tests

```text
php bin/phpunit --coverage-html <folder>
```

## 8 • Créez une branche de déploiement de vos modifications 

```text
git branch <branch-name>
```

## 9 • Basculez sur cette branche

```text
git checkout <branch-name>
```

## 10 • Envoyez vos nouveaux fichiers sur cette branche

```text
git add
git commit
git push -u origin <branch-name>
```

## 11 • Documentez vos modifications dans une Pull request

Consultez ce [tutoriel Github](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/about-pull-requests)

## 12 • Supprimez votre branche de déploiement

```text
git branch -d <branch-name>
```

## 13 • En option, effectuez des comparaisons de performances de l'application suite à vos modifications en utilisant l'extension Blackfire

Extension Google Chrome [Blackfire Profiler](https://chrome.google.com/webstore/detail/blackfire-profiler/miefikpgahefdbcgoiicnmpbeeomffld?hl=fr&pageId=108863020526025791688)

### Processus de qualité à utiliser et règles à respecter

``` bash
* Pour les tests unitaires ou fonctionnels, veillez à proposer une couverture de code qui soit supérieure à 70%

* Elaborez une documentation technique pour toutes les nouvelles fonctionnalités ajoutées au projet

* Réalisez des tests de qualité de code et de performances en proposant des métriques

* Elaborez un document permettant de comprendre comment contribuer au projet modifié
```
