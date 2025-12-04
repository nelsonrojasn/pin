<?php

namespace App;

/**
 * Autoloader PSR-4
 * Carga automáticamente las clases de la aplicación
 */
class Autoloader
{
    /**
     * Array de namespaces mapeados a directorios
     */
    private static $prefixes = [];

    /**
     * Registra el autoloader
     */
    public static function register()
    {
        spl_autoload_register([self::class, 'load']);
    }

    /**
     * Añade un namespace y su directorio base
     * @param string $prefix Namespace (ej: 'App\\Libs')
     * @param string $base_dir Directorio base (ej: '/path/to/app/libs')
     */
    public static function addNamespace($prefix, $base_dir)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $base_dir = rtrim($base_dir, '/\\') . DIRECTORY_SEPARATOR;
        self::$prefixes[$prefix] = $base_dir;
    }

    /**
     * Carga la clase
     * @param string $class Nombre completo de la clase con namespace
     */
    public static function load($class)
    {
        foreach (self::$prefixes as $prefix => $base_dir) {
            if (strpos($class, $prefix) === 0) {
                $relative_class = substr($class, strlen($prefix));
                $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

                if (file_exists($file)) {
                    require $file;
                    return true;
                } else {
                    error_log("Autoloader: No se encontró el archivo $file para la clase $class");
                }
            }
        }

        return false;
    }
}
