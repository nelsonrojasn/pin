<?php

//cargar la sesion de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . "/bootstrap.php";

// Obtener URL actual
$url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

// Cargar y registrar rutas
$routes = require ROOT . DS . "routes.php";

//cargar helpers globales
\Pin\Libs\Load::helper('html_tags');

// Crear router y ejecutar
$router = new \App\Router();
$router->registerRoutes($routes);
$router->dispatch($url);




