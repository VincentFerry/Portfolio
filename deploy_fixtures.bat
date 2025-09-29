@echo off
echo 🚀 Deploiement des fixtures en production

echo 📦 Backup de la base de donnees...
php bin/console doctrine:query:sql "CREATE TABLE IF NOT EXISTS backup_projects_%date:~-4,4%%date:~-10,2%%date:~-7,2% AS SELECT * FROM projects"

echo 🗑️ Nettoyage des donnees existantes...
php-8.2 bin/console doctrine:query:sql "SET FOREIGN_KEY_CHECKS = 0"
php-8.2 bin/console doctrine:query:sql "TRUNCATE TABLE project_images"
php-8.2 bin/console doctrine:query:sql "TRUNCATE TABLE projects"
php-8.2 bin/console doctrine:query:sql "SET FOREIGN_KEY_CHECKS = 1"

echo 📥 Chargement des nouvelles donnees...
php-8.2 bin/console doctrine:fixtures:load --no-interaction

echo ✅ Verification des donnees...
php-8.2 bin/console doctrine:query:sql "SELECT COUNT(*) as projects_count FROM projects"

echo 🧹 Nettoyage du cache...
php bin/console cache:clear --env=prod

echo 🎉 Deploiement termine avec succes !
pause
