<?php

//cargar la sesion de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . "/bootstrap.php";


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

load_helper("html_tags");

load_page_from_url($url);




