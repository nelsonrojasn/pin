<?php
define('PIN_START_TIME', microtime(true));
define('PIN_START_MEM', memory_get_usage());

define('ROOT', dirname(__FILE__)); 
define('DS', DIRECTORY_SEPARATOR);
const PIN_PATH = ROOT . DS . 'pin' . DS;
require PIN_PATH . 'config' . DS . 'settings.php';

// 0. Escudo contra peticiones excesivamente grandes
if (isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > MAX_REQUEST_SIZE) {
    http_response_code(413);
    exit('Error 413: El tamaño de la petición excede el límite permitido.');
}

//cargar la sesion de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 0.1 Escudo contra peticiones consecutivas (Rate Limiting por Sesión)
$last_request = $_SESSION['_pin_last_req'] ?? 0;
$current_time = microtime(true);
if (RATE_LIMIT_MS > 0 && ($current_time - $last_request) < (RATE_LIMIT_MS / 1000)) {
    http_response_code(429);
    exit('Error 429: Demasiadas peticiones. Por favor, espere un momento.');
}
$_SESSION['_pin_last_req'] = $current_time;

// 1. Capturamos la URL limpia por PATH_INFO
$url = $_SERVER['PATH_INFO'] ?? '/';

/**
 * Activar en producción
 */
//ini_set("error_reporting", 0);
//ini_set("display_errors", 0);

require ROOT . DS . "load.php";
route($url);
