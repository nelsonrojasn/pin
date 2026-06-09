<?php
define('PIN_START_TIME', microtime(true));
define('PIN_START_MEM', memory_get_usage());

//cargar la sesion de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

define('ROOT', dirname(__FILE__)); 
define('DS', DIRECTORY_SEPARATOR);
const PIN_PATH = ROOT . DS . 'pin' . DS;
require PIN_PATH . 'config' . DS . 'settings.php';

// 1. Capturamos la URL limpia por PATH_INFO
$url = $_SERVER['PATH_INFO'] ?? '/';

ini_set("error_reporting", 0);
ini_set("display_errors", 0);

require ROOT . DS . "load.php";
route($url);
