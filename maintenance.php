<?php
/**
 * Script de gestion de la maintenance
 * Usage: php maintenance.php [on|off|status]
 */

$maintenanceFile = __DIR__ . '/public/.maintenance';
$htaccessFile = __DIR__ . '/public/.htaccess';

function showUsage() {
    echo "Usage: php maintenance.php [on|off|status]\n";
    echo "  on     - Active le mode maintenance\n";
    echo "  off    - Désactive le mode maintenance\n";
    echo "  status - Affiche l'état actuel\n";
}

function enableMaintenance() {
    global $maintenanceFile, $htaccessFile;
    
    // Créer le fichier de maintenance
    file_put_contents($maintenanceFile, date('Y-m-d H:i:s'));
    
    // Backup du .htaccess actuel
    if (file_exists($htaccessFile)) {
        copy($htaccessFile, $htaccessFile . '.backup');
    }
    
    // Créer le .htaccess de maintenance
    $htaccessContent = "# Mode maintenance activé le " . date('Y-m-d H:i:s') . "\n";
    $htaccessContent .= "RewriteEngine On\n";
    $htaccessContent .= "RewriteCond %{REQUEST_URI} !^/maintenance.html$\n";
    $htaccessContent .= "RewriteCond %{REQUEST_URI} !^/images/\n";
    $htaccessContent .= "RewriteCond %{REQUEST_URI} !^/build/\n";
    $htaccessContent .= "RewriteRule ^(.*)$ /maintenance.html [R=503,L]\n";
    $htaccessContent .= "Header always set Retry-After \"300\"\n";
    
    file_put_contents($htaccessFile, $htaccessContent);
    
    echo "✅ Mode maintenance ACTIVÉ\n";
    echo "📄 Page visible sur: /maintenance.html\n";
    echo "🔄 Le site redirige automatiquement vers la page de maintenance\n";
}

function disableMaintenance() {
    global $maintenanceFile, $htaccessFile;
    
    // Supprimer le fichier de maintenance
    if (file_exists($maintenanceFile)) {
        unlink($maintenanceFile);
    }
    
    // Restaurer le .htaccess original
    if (file_exists($htaccessFile . '.backup')) {
        rename($htaccessFile . '.backup', $htaccessFile);
        echo "✅ Mode maintenance DÉSACTIVÉ\n";
        echo "🔄 .htaccess original restauré\n";
    } else {
        // Supprimer le .htaccess de maintenance
        if (file_exists($htaccessFile)) {
            unlink($htaccessFile);
        }
        echo "✅ Mode maintenance DÉSACTIVÉ\n";
        echo "⚠️  Aucun .htaccess de sauvegarde trouvé\n";
    }
    
    echo "🚀 Le site est de nouveau accessible normalement\n";
}

function getStatus() {
    global $maintenanceFile;
    
    if (file_exists($maintenanceFile)) {
        $timestamp = file_get_contents($maintenanceFile);
        echo "🚧 Mode maintenance ACTIF depuis: $timestamp\n";
        echo "📄 Page de maintenance: /maintenance.html\n";
    } else {
        echo "✅ Mode maintenance INACTIF\n";
        echo "🚀 Le site est accessible normalement\n";
    }
}

// Traitement des arguments
$action = $argv[1] ?? '';

switch ($action) {
    case 'on':
        enableMaintenance();
        break;
    case 'off':
        disableMaintenance();
        break;
    case 'status':
        getStatus();
        break;
    default:
        showUsage();
        exit(1);
}
?>
