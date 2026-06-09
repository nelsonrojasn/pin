<?php

/**
* CLAVE DE ENCRIPTACION PARA DATOS SENSIBLES
*
**/
define('CRIPTO_KEY', 'AX18-12A.AaC4n7.@$%&@#');

//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/');

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Database Configuration (SQLite3 with performance optimizations)
define('DB_PATH', '/var/www/pin_apps/db/blog.sqlite');
define('DB_HOST', 'sqlite:' . DB_PATH);
define('DB_USER', '');
define('DB_PASS', '');
