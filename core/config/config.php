<?php

// Reportar todos los errores de PHP
error_reporting(E_ALL);

// Mostrar errores en el navegador
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define('BASE_DIR', 'c:/Apache24/htdocs/vlexplosion.org/');
} else {
    define('BASE_DIR', '/var/www/html/vlexplosion.org/');
}
