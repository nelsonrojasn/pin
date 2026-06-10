<?php

/**
* CLAVE DE ENCRIPTACION PARA DATOS SENSIBLES
*
**/
define('CRIPTO_KEY', hash('sha256', 'AX18-12A.AaC4n7.@$%&@#_PinZeroSecret', true)); // Clave de 32 bytes para AES-256

//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/');

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Database Configuration (SQLite3 with performance optimizations)
define('DB_PATH', PIN_PATH . 'db' . DS. 'app.sqlite');
define('DB_HOST', 'sqlite:' . DB_PATH);
define('DB_USER', '');
define('DB_PASS', '');

// Límite máximo de tamaño de petición (ejemplo: 5MB)
define('MAX_REQUEST_SIZE', 5 * 1024 * 1024);

// Tiempo mínimo entre peticiones en milisegundos (0 para desactivar)
define('RATE_LIMIT_MS', 200);

define('ALLOW_SETUP', false);
