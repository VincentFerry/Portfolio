<?php

// Check if the intl extension is loaded
if (extension_loaded('intl')) {
    echo "✅ PHP Intl extension is enabled.\n";
    echo "Intl version: " . INTL_ICU_VERSION . "\n";
} else {
    echo "❌ PHP Intl extension is NOT enabled.\n";
    echo "Please enable the intl extension in your php.ini file.\n";
}

// Show PHP info about intl
echo "\nPHP Info for intl extension:\n";
ob_start();
phpinfo(INFO_MODULES);
$phpinfo = ob_get_clean();

if (preg_match('/intl.*?<td[^>]*>([^<]+)/is', $phpinfo, $matches)) {
    echo "Intl status: " . trim($matches[1]) . "\n";
}

// Show location of php.ini
echo "\nPHP configuration file: " . php_ini_loaded_file() . "\n";
