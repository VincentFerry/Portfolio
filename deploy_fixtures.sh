#!/bin/bash

# Script de déploiement des fixtures en production
echo "🚀 Déploiement des fixtures en production"

# 1. Backup de la base de données
echo "📦 Backup de la base de données..."
php bin/console doctrine:query:sql "CREATE TABLE IF NOT EXISTS backup_projects_$(date +%Y%m%d) AS SELECT * FROM projects"
php bin/console doctrine:query:sql "CREATE TABLE IF NOT EXISTS backup_project_images_$(date +%Y%m%d) AS SELECT * FROM project_images"

# 2. Vider les tables existantes
echo "🗑️ Nettoyage des données existantes..."
php bin/console doctrine:query:sql "SET FOREIGN_KEY_CHECKS = 0"
php bin/console doctrine:query:sql "TRUNCATE TABLE project_images"
php bin/console doctrine:query:sql "TRUNCATE TABLE projects"
php bin/console doctrine:query:sql "SET FOREIGN_KEY_CHECKS = 1"

# 3. Charger les fixtures
echo "📥 Chargement des nouvelles données..."
php bin/console doctrine:fixtures:load --no-interaction

# 4. Vérification
echo "✅ Vérification des données..."
php bin/console doctrine:query:sql "SELECT COUNT(*) as projects_count FROM projects"
php bin/console doctrine:query:sql "SELECT COUNT(*) as images_count FROM project_images"

# 5. Vider le cache
echo "🧹 Nettoyage du cache..."
php bin/console cache:clear --env=prod

echo "🎉 Déploiement terminé avec succès !"
