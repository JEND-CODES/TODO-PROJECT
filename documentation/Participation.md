# Comment contribuer au projet ?

## 1 • Clonez le projet

```text
git clone https://github.com/JEND-CODES/TODO-PROJECT
```

## 2 • Installez les dépendances avec Composer

```text
composer install
```

## 3 • Indiquez l'accès à votre base de données dans le fichier .env, puis créez-la :

```text
php bin/console doctrine:database:create
```

## 4 • Générez les fixtures :

```text
php bin/console doctrine:fixtures:load
```

## 5 • Apportez vos modifications au projet

## 6 • Réalisez des tests unitaires de vos modifications :

```text
php bin/phpunit
```

## 7 • Créez éventuellement un dossier de couverture html des tests :

```text
php bin/phpunit --coverage-html <folder>
```

## 8 • Créez une branche de déploiement de vos modifications : 

```text
git branch <branch-name>
```

## 9 • Basculez sur cette branche :

```text
git checkout <branch-name>
```

## 10 • Envoyez vos nouveaux fichiers sur cette branche :

```text
git add
git commit
git push -u origin <branch-name>
```

## 11 • Documentez vos modifications dans une Pull request

## 12 • Supprimez votre branche de déploiement :

```text
git branch -d <branch-name>
```

## 13 • En option, analysez une comparaison des performances de l'application suite à vos modifications en utilisant l'extension Blackfire
