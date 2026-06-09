<?php

// Load database functions
require PIN_PATH . 'libs' . DS . 'db.php';

// Load session functions
require PIN_PATH . 'libs' . DS . 'session.php';

// Load cipher functions (encryption/decryption)
require PIN_PATH . 'libs' . DS . 'cipher.php';

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
    $file = PIN_PATH . 'views' . DS . $view . '.phtml';
    if (!file_exists($file)) {
        throw new Exception("Vista no encontrada: $view", 500);
    }

    if ($params) extract($params);
    require $file;
}

// Cargar partials con parámetros locales
function load_partial(string $partial, array|null $params = null)
{
    $file = PIN_PATH . 'partials' . DS . $partial . '.phtml';
    if (!file_exists($file)) {
        return "<!-- Error: Partial $partial no encontrado -->";
    }

    if ($params) extract($params);
    require $file;
}

// Cargar helpers
function load_helper(string $helper)
{
    require_once PIN_PATH . 'helpers' . DS . $helper . '.php';
}

// Redirigir al navegador
function redirect_to(string $url)
{
    $base = defined('PUBLIC_PATH') ? PUBLIC_PATH : '/';
    header('Location: ' . $base . ltrim($url, '/'), true, 302);
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
    $code = $e->getCode();
    // Si el código no es un error de cliente (4xx), asumimos error de servidor (500)
    if ($code < 400 || $code > 499) {
        $code = 500;
    }

    if (!headers_sent()) {
        http_response_code($code);
    }
    
    if (error_reporting()) {
        echo "<div style='padding: 40px; font-family: monospace;'>";
        echo "<h1 style='color: #d32f2f;'>Error " . $code . "</h1>";
        echo "<p><strong>" . get_class($e) . ":</strong> " . $e->getMessage() . "</p>";
        echo "<pre style='background: #eee; padding: 15px; overflow-x: auto;'>" . $e->getTraceAsString() . "</pre>";
        echo "</div>";
    } else {
        try {
            load_view("errors/default", ['code' => $code, 'exception' => $e]);
        } catch (Exception $fallback) {
            echo "<h1>Error {$code}</h1>";
            echo "<p>Lo sentimos, ha ocurrido un error inesperado.</p>";
        }
    }
});

// Enrutador principal (Evolución a POO)
function route(string $url)
{
    $allowed_pages = include PIN_PATH . 'config' . DS . 'routes.php';
    $r_param = request_get('r', 'string');

    // 1. RESOLUCIÓN: Determinamos qué quiere el usuario (Cifrado o Home)
    if (!empty($r_param)) {
        try {
            list($page, $action, $parameters) = parse_url_hash($r_param);
        } catch (Exception $e) {
            throw new Exception("404 - El recurso solicitado no existe.", 404);
        }
    } elseif ($url === '' || $url === '/') {
        // Escudo de contrabando: si no es 'r' ni '/', no se permiten parámetros $_GET sueltos
        if (count($_GET) > 0) {
            throw new Exception("404 - El recurso solicitado no existe.", 404);
        }
        $page = 'default';
        $action = 'index';
        $parameters = [];
    } else {
        throw new Exception("404 - El recurso solicitado no existe.", 404);
    }

    // 2. VALIDACIÓN DE CONFIGURACIÓN
    $config = $allowed_pages[$page] ?? throw new Exception("404 - El recurso solicitado no existe.", 404);
    $is_private = (($config['type'] ?? 'public') === 'private');

    // 3. SEGURIDAD DE ACCESO Y ARCHIVO
    if ($is_private) {
        if (session_get('is_logged_in') === null) {
            session_set('flash', 'Acceso restringido. Por favor, inicie sesión.');
            redirect_to('/');
        }
        if (!has_permission($page, $action)) {
            session_set('flash', 'Acceso restringido. Sin permisos!');
            redirect_to('/');
        }
    }

    $file = PIN_PATH . 'pages' . DS . ($is_private ? 'private' . DS : '') . $page . '.php';
    if (!file_exists($file)) {
        throw new Exception("404 - El recurso solicitado no existe.", 404);
    }

    // 4. REHIDRATACIÓN: Limpieza total de $_GET inyectando solo lo validado
    $_GET = array_merge(['page' => $page, 'action' => $action], $parameters);
    
    require_once $file;
    $class_name = ucfirst($page) . 'Page';
    if (!class_exists($class_name)) {
        throw new Exception("404 - El recurso solicitado no existe.", 404);
    }
    
    $page_object = new $class_name();
    
    try {
        // Reflection nos sirve de validador de métodos públicos y sanitizador implícito
        $reflection = new ReflectionMethod($page_object, $action);
        if (!$reflection->isPublic()) {
            throw new Exception("403 - Acción no permitida", 403);
        }
    } catch (ReflectionException $e) {
        throw new Exception("404 - El recurso solicitado no existe.", 404);
    }
    
    if (method_exists($page_object, 'page_initializer')) {
        $page_object->page_initializer();
    }
    
    return $page_object->$action();
}
