# 🚀 Guide de Déploiement - Migration des Données

## 📋 Vos Fixtures Prêtes

Vos vraies données ont été converties en fixtures dans `src/DataFixtures/ProjectFixtures.php` :

### Projets Inclus :
1. **Leekwars** - Jeu de programmation IA
2. **Portfolio** - Votre portfolio Symfony
3. **Miroir connecté** - Projet Raspberry Pi

## 🔧 Installation en Production

### 1. Installer le Bundle Fixtures
```bash
composer require --dev doctrine/doctrine-fixtures-bundle
```

### 2. Uploader les Fichiers
- `src/DataFixtures/ProjectFixtures.php`
- `public/images/projects/` (vos images)

### 3. Déployer les Données
```bash
# Backup de sécurité
php bin/console doctrine:query:sql "CREATE TABLE backup_projects_$(date +%Y%m%d) AS SELECT * FROM projects"

# Vider les tables
php bin/console doctrine:query:sql "TRUNCATE TABLE project_images"
php bin/console doctrine:query:sql "TRUNCATE TABLE projects"

# Charger les fixtures
php bin/console doctrine:fixtures:load --no-interaction

# Vérifier
php bin/console doctrine:query:sql "SELECT COUNT(*) FROM projects"
```

## 📁 Images Nécessaires

Assurez-vous d'avoir ces images dans `public/images/projects/` :
- `leekwars-1.jpg`
- `portfolio-1.jpg` 
- `miroir-1.jpg`

## ✅ Vérification

Après déploiement, vérifiez :
- [ ] 3 projets visibles sur le site
- [ ] Images qui s'affichent correctement
- [ ] Liens GitHub/démo fonctionnels
- [ ] Traductions FR/EN

## 🆘 En Cas de Problème

Si erreur, restaurer la sauvegarde :
```bash
php bin/console doctrine:query:sql "INSERT INTO projects SELECT * FROM backup_projects_YYYYMMDD"
```

## 🎯 Commande Rapide

Pour déployer rapidement :
```bash
./deploy_fixtures.sh
```

Ou sur Windows :
```cmd
deploy_fixtures.bat
```
