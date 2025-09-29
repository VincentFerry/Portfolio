#!/bin/bash

# Script de déploiement des fixtures en production
echo "🚀 Déploiement des fixtures en production"

# 0. Installer le bundle fixtures si nécessaire
echo "📦 Installation du bundle fixtures..."
composer require --dev doctrine/doctrine-fixtures-bundle --no-interaction

# 1. Backup de la base de données (SQLite compatible)
echo "📦 Backup de la base de données..."
php-8.2 bin/console doctrine:query:sql "CREATE TABLE backup_projects_$(date +%Y%m%d) AS SELECT * FROM projects"
php-8.2 bin/console doctrine:query:sql "CREATE TABLE backup_project_images_$(date +%Y%m%d) AS SELECT * FROM project_images"

# 2. Vider les tables existantes (SQLite compatible)
echo "🗑️ Nettoyage des données existantes..."
php-8.2 bin/console doctrine:query:sql "DELETE FROM project_images"
php-8.2 bin/console doctrine:query:sql "DELETE FROM projects"

# 3. Charger les fixtures
echo "📥 Chargement des nouvelles données..."
php-8.2 bin/console doctrine:fixtures:load --no-interaction

# 4. Vérification
echo "✅ Vérification des données..."
php-8.2 bin/console doctrine:query:sql "SELECT COUNT(*) as projects_count FROM projects"
php-8.2 bin/console doctrine:query:sql "SELECT COUNT(*) as images_count FROM project_images"

# 5. Vider le cache
echo "🧹 Nettoyage du cache..."
php-8.2 bin/console cache:clear --env=prod

echo "🎉 Déploiement terminé avec succès !"
