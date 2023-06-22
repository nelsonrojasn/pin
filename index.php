<?php

//cargar la sesion de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

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


$url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

//convenciones
/*
la $url contendrá como primer parámetro el nombre de una página,
el segundo parámetro será un método que debe ejecutarse en dicha página
si hay más parámetros deben pasarse a la función
ejemplo: page/show/contactenos
cargará la página page.php, y buscará una función llamada show a la que le
pasará como parámetro contactenos
*/

require ROOT . DS . "load.php";

load_page_from_url($url);