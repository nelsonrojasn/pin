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
}

