<?php
//definir el separador de directorio
const DS = DIRECTORY_SEPARATOR; 
//definir la raíz del sitio
define('ROOT', dirname(__FILE__)); 
define('PIN_PATH', ROOT . DS . 'pin' . DS);
define('APP_PATH', ROOT . DS . 'app' . DS);

//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/');

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Registrar autoloader PSR-4
require APP_PATH . 'Autoloader.php';
\App\Autoloader::register();
\App\Autoloader::addNamespace('App\\Handlers', APP_PATH . 'handlers');
\App\Autoloader::addNamespace('App\\Libs', APP_PATH . 'libs');
\App\Autoloader::addNamespace('App\\Helpers', APP_PATH . 'helpers');
\App\Autoloader::addNamespace('App', APP_PATH);
// Mapeo para clases históricas bajo el namespace Pin\\Libs
\App\Autoloader::addNamespace('Pin\\Libs', PIN_PATH . 'libs');

// Manejadores de errores y excepciones
set_error_handler('handle_error');
set_exception_handler('handle_exception');

function handle_error($level, $message, $file, $line)
{
    if (error_reporting() !== 0) {  
        throw new \ErrorException($message, 0, $level, $file, $line);
    }
}

function handle_exception($exception)
{
    $code = $exception->getCode();
    if ($code != 404) {
        $code = 500;
    }
    http_response_code($code);

    if (error_reporting() !== 0) {
        echo "<div style='padding: 40px;'>";
		echo "<h1>Fatal error</h1>";
        echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
        echo "<p>Message: '" . $exception->getMessage() . "'</p>";
        echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
        echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
		echo "</div>";
    }
}