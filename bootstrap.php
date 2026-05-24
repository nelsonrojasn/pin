<?php
//definir el separador de directorio
const DS = DIRECTORY_SEPARATOR; 
//definir la raíz del sitio
define('ROOT', dirname(__FILE__)); 
define('PIN_PATH', ROOT . DS . 'pin' . DS);

//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/');

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Database Configuration (SQLite3 with performance optimizations)
define('DB_PATH', ROOT . DS . 'db' . DS . 'app.sqlite');
define('DB_HOST', 'sqlite:' . DB_PATH);
define('DB_USER', '');
define('DB_PASS', '');