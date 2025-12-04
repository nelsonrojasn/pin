<?php

/**
 * routes.php
 * Definición de todas las rutas de la aplicación
 * Formato: 'MÉTODO /ruta' => 'NombreHandler'
 */

return [
    // Rutas públicas
    'GET /' => 'DefaultHandler',
    'GET /login/signin' => 'LoginHandler',
    'GET /login/signout' => 'LogoutHandler',
    'POST /login/signin' => 'LoginHandler',
    'POST /login/signout' => 'LoginHandler',

    // Rutas protegidas (page)
    'GET /page/show' => 'PageHandler',
    'GET /page/show/{slug}' => 'PageHandler',
    'GET /page/edit' => 'PageHandler',
    'POST /page/update' => 'PageHandler',
];
