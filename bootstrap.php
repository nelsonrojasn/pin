<?php
//definir el separador de directorio
const DS = DIRECTORY_SEPARATOR; 
//definir la raíz del sitio
define('ROOT', dirname(__FILE__)); 
define('PIN_PATH', ROOT . DS . 'pin' . DS);

//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/pin/');

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');
//date_default_timezone_set("America/Santiago");
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');