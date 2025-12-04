<?php

namespace App;

use App\Handlers\Handler;

/**
 * Router
 * Gestiona el enrutamiento de la aplicación
 */
class Router
{
    /**
     * Array de rutas registradas
     * Formato: ['GET|POST|PUT|DELETE /path' => 'Handler\Class']
     */
    private $routes = [];

    /**
     * Registra una ruta
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $path Ruta (ej: /page, /page/show)
     * @param string $handler Clase handler (ej: PageHandler)
     */
    public function register($method, $path, $handler)
    {
        $key = "$method $path";
        $this->routes[$key] = $handler;
    }

    /**
     * Registra múltiples rutas
     * @param array $routes Array de rutas
     */
    public function registerRoutes(array $routes)
    {
        foreach ($routes as $key => $handler) {
            list($method, $path) = explode(' ', $key, 2);
            $this->register($method, $path, $handler);
        }
    }

    /**
     * Procesa la URL actual y ejecuta el handler correspondiente
     * @param string $url URL a procesar (ej: /page/show/contactenos)
     */
    public function dispatch($url)
    {
        // Normalizar URL
        $url = parse_url($url, PHP_URL_PATH);
        $url = rtrim($url, '/') ?: '/';

        $method = $_SERVER['REQUEST_METHOD'];

        // Intentar encontrar una ruta exacta
        $key = "$method $url";
        if (isset($this->routes[$key])) {
            return $this->executeHandler($this->routes[$key], []);
        }

        // Intentar coincidencias con parámetros
        foreach ($this->routes as $route_key => $handler) {
            list($route_method, $route_path) = explode(' ', $route_key, 2);
            
            if ($route_method !== $method) {
                continue;
            }

            // Convertir ruta a regex para capturar parámetros
            // /page/{slug} -> /page/(.+)
            $pattern = preg_replace('/{[^}]+}/', '([^/]+)', $route_path);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches)) {
                // Remover el primer elemento que es la URL completa
                array_shift($matches);
                return $this->executeHandler($handler, $matches);
            }
        }

        // Si llegamos aquí, la ruta no existe
        http_response_code(404);
        throw new \Exception("Ruta no encontrada: $method $url", 404);
    }

    /**
     * Ejecuta un handler
     * @param string $handler_class Nombre de la clase handler
     * @param array $params Parámetros extraídos de la URL
     */
    private function executeHandler($handler_class, array $params = [])
    {
        $class_name = 'App\\Handlers\\' . $handler_class;

        if (!class_exists($class_name)) {
            throw new \Exception("Handler no existe: $class_name", 500);
        }

        $handler = new $class_name();

        if (!$handler instanceof Handler) {
            throw new \Exception("$class_name no extiende Handler", 500);
        }

        return $handler->handle($params);
    }
}
