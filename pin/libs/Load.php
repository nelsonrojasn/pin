<?php

namespace Pin\Libs;

/**
 * Load
 * Clase con métodos estáticos para cargar archivos, helpers, vistas y manejar
 * el enrutamiento dinámico antiguo si es necesario.
 */
class Load
{
    public static function file($file, $extension, $base_dir, $classification, $parameters = null)
    {
        if (isset($parameters) && is_array($parameters)) {
            extract($parameters);
        }
        $path = $base_dir . $file . $extension;
        if (file_exists($path)) {
            require $path;
        } else {
            throw (new \Exception("$classification <b>$file</b> no existe!"));
        }
    }

    public static function lib($lib)
    {
        self::file($lib, ".php", PIN_PATH . 'libs' . DS, "Librería");
    }

    public static function config($config)
    {
        self::file($config, ".php", PIN_PATH . 'config' . DS, "Configuración");
    }

    public static function helper($helper)
    {
        self::file($helper, ".php", PIN_PATH . 'helpers' . DS, "Helper");
    }

    public static function view($view, $parameters = null)
    {
        self::file($view, ".phtml", PIN_PATH . 'views' . DS, "Vista", $parameters);
    }

    public static function partial($partial, $parameters = null)
    {
        self::file($partial, ".phtml", PIN_PATH . 'partials' . DS, "Parcial", $parameters);
    }

    public static function redirect($url)
    {
        $url = PUBLIC_PATH . $url;
        header("Location: $url", true, 301);
        exit;
    }

    // --- Funciones del router dinámico previo (compatibilidad) ---
    public static function pageFromUrl($url)
    {
        // Validar entrada
        if (!is_string($url)) {
            throw new \InvalidArgumentException('URL must be a string');
        }

        // Extraer componentes de la URL
        $components = array_values(array_filter(explode('/', $url)));

        // Determinar página y función
        $page = $components[0] ?? 'default';
        $function = $components[1] ?? 'index';
        $params = array_slice($components, 2);

        // Validar existencia de la página
        $page_path = PIN_PATH . 'pages' . DS . $page . '.php';
        if (!file_exists($page_path)) {
            throw new \Exception("La página <b>$page</b> no existe!");
        }

        require $page_path;

        // Ejecutar inicializador si existe
        if (function_exists('page_initializer')) {
            page_initializer();
        }

        // Determinar la función a ejecutar
        $function_to_call = self::determineFunctionToCall($function);

        if (empty($function_to_call)) {
            throw new \Exception("La función <b>$function</b> no existe en la página <b>$page</b>!");
        }

        return call_user_func_array($function_to_call, $params);
    }

    public static function determineFunctionToCall($function)
    {
        if (function_exists($function)) {
            return $function;
        }

        $request_method = strtolower($_SERVER['REQUEST_METHOD']);
        if (function_exists($request_method)) {
            return $request_method;
        }

        return null;
    }
}

