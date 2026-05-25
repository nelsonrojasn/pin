<?php

// Load database functions
require PIN_PATH . 'libs' . DS . 'db.php';

// Load session functions
require PIN_PATH . 'libs' . DS . 'session.php';

// Load request functions
require PIN_PATH . 'libs' . DS . 'request.php';

// Load acl functions
require PIN_PATH . 'libs' . DS . 'acl.php';

// Cargar helpers globals
require_once PIN_PATH . 'helpers' . DS . 'html_tags.php';
require_once PIN_PATH . 'helpers' . DS . 'form_tags.php';

// Cargar vistas con parámetros locales
function load_view(string $view, array|null $params = null)
{
    if ($params) extract($params);
    require PIN_PATH . 'views' . DS . $view . '.phtml';
}

// Cargar partials con parámetros locales
function load_partial(string $partial, array|null $params = null)
{
    if ($params) extract($params);
    require PIN_PATH . 'partials' . DS . $partial . '.phtml';
}

// Cargar helpers
function load_helper(string $helper)
{
    require_once PIN_PATH . 'helpers' . DS . $helper . '.php';
}

// Redirigir al navegador
function redirect_to(string $url)
{
    header('Location: ' . PUBLIC_PATH . $url, true, 301);
    exit;
}

// Autoloader: PascalCase → snake_case
spl_autoload_register(function($className) {
    $file = PIN_PATH . 'libs' . DS . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . '.php';
    if (!file_exists($file)) {
        throw new Exception("Clase no existe: $className ($file)");
    }
    require_once $file;
});

// Manejadores de error y excepción
set_error_handler(fn($level, $msg, $file, $line) => 
    throw new ErrorException($msg, 0, $level, $file, $line)
);

set_exception_handler(function($e) {
    $code = ($e->getCode() === 404) ? 404 : 500;
    http_response_code($code);
    
    if (error_reporting()) {
        echo "<div style='padding: 40px; font-family: monospace;'>";
        echo "<h1>Error (" . $code . ")</h1>";
        echo "<p><strong>" . get_class($e) . ":</strong> " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "</div>";
    }
});

// Enrutador principal
function route(string $url)
{
    if (preg_match('/\.(sqlite|db|config|log)$/', $url)) {
        throw new Exception("403 - Acceso prohibido.");
    }
    
    $parts = array_values(array_filter(explode('/', $url)));
    
    // Limpieza de seguridad para evitar saltos de directorio
    $page = str_replace(['.', '/'], '', $parts[0] ?? 'default');
    $function = $parts[1] ?? 'index';
    $args = array_slice($parts, 2);

    // Estrategia de búsqueda: 1. Público -> 2. Privado
    $file = PIN_PATH . 'pages' . DS . $page . '.php';
    $is_private = false;

    if (!file_exists($file)) {
        $file = PIN_PATH . 'pages' . DS . 'private' . DS . $page . '.php';
        $is_private = true;
    }

    // Validación de existencia y seguridad
    if (!file_exists($file)) {
        throw new Exception("404 - El recurso solicitado no existe.");
    }

    if ($is_private && empty(session_get('is_logged_in'))) {
        session_set('flash', 'Acceso restringido. Por favor, inicie sesión.');
        redirect_to('');
    }

    if ($is_private && !has_permission($page, $function)) {
        session_set('flash', 'Acceso restringido. Sin permisos para este recurso!');
        redirect_to('');
    }

    require $file;

    // Ciclo de vida de la página
    if (function_exists('page_initializer')) {
        page_initializer();
    }

    if (!function_exists($function)) {
        throw new Exception("404 - Acción no encontrada.");
    }

    return call_user_func_array($function, $args);
}