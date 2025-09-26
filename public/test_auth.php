<?php
// Script de test pour vérifier l'authentification
// À supprimer après les tests

echo "=== Test de l'authentification ===\n";

// Vérifier si la table users existe
try {
    $pdo = new PDO('sqlite:../var/data.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier la structure de la table users
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    $table = $stmt->fetch();
    
    if ($table) {
        echo "✅ Table 'users' existe\n";
        
        // Vérifier les colonnes
        $stmt = $pdo->query("PRAGMA table_info(users)");
        $columns = $stmt->fetchAll();
        
        echo "Colonnes de la table users:\n";
        foreach ($columns as $column) {
            echo "  - {$column['name']} ({$column['type']})\n";
        }
        
        // Compter les utilisateurs
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch()['count'];
        echo "Nombre d'utilisateurs: {$count}\n";
        
    } else {
        echo "❌ Table 'users' n'existe pas\n";
        echo "Exécutez: php bin/console doctrine:migrations:migrate\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
    echo "Vérifiez que la base de données existe et que les migrations ont été exécutées.\n";
}

echo "\n=== Instructions ===\n";
echo "1. Exécutez: php bin/console doctrine:migrations:migrate\n";
echo "2. Créez un admin: php bin/console app:create-admin admin@example.com password123\n";
echo "3. Testez la connexion sur /login\n";
echo "4. Supprimez ce fichier après les tests\n";
?>
