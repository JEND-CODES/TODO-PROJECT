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

Consultez la documentation sur [l'authentification](https://github.com/JEND-CODES/TODO-PROJECT)

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