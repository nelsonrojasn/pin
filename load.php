<?php

function load_file($file, $extension, $base_dir, $classification, $parameters = null)
{
	if (isset($parameters) && is_array($parameters)) {
		extract($parameters);
	}
	if (file_exists($base_dir . $file . $extension)) {	
		require $base_dir . $file . $extension;
	} else {
		throw (new Exception("$classification <b>$file</b> no existe!"));	
	}
}

function load_lib($lib)
{
	load_file($lib, ".php", PIN_PATH . 'libs' . DS, "Librería");
}

function load_config($config)
{
	load_file($config, ".php", PIN_PATH . 'config' . DS, "Configuración");
}


function load_helper($helper)
{
	load_file($helper, ".php", PIN_PATH . 'helpers' . DS, "Helper");
}

function load_view($view, array $parameters = null)
{
	load_file($view, ".phtml", PIN_PATH . 'views' . DS, "Vista", $parameters);
}

function load_partial($partial, array $parameters = null)
{
	load_file($partial, ".phtml", PIN_PATH . 'partials'. DS , "Parcial", $parameters);		
}

function redirect_to($url)
{
	$url = PUBLIC_PATH . $url;
	header("Location: $url", true, 301);
}

//implementar autoloader para clases
//espera que las clases se definan en PascalCase y los archivos
//usen snake_case. Ejemplo class QueryBuilder, archivo query_builder.php
spl_autoload_register(function($className){
	$file_name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
	$file = PIN_PATH . 'libs' . DS . $file_name . '.php';
	if (file_exists($file)) {
		require_once $file;
		return;
	} else {
		throw new Exception("$className no existe en $file", 1);		
	}
});


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


function load_page_from_url($url)
{
    // Validar entrada
    if (!is_string($url)) {
        throw new InvalidArgumentException('URL must be a string');
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
        throw new Exception("La página <b>$page</b> no existe!");
    }
    
    require $page_path;
    
    // Ejecutar inicializador si existe
    if (function_exists('page_initializer')) {
        page_initializer();
    }
    
    // Determinar la función a ejecutar
    $function_to_call = determine_function_to_call($function);
    
    if (empty($function_to_call)) {
        throw new Exception("La función <b>$function</b> no existe en la página <b>$page</b>!");
    }
    
    return call_user_func_array($function_to_call, $params);
}

/**
 * Determina qué función debe ser llamada basada en el nombre de la función
 * y el método de la petición
 */
function determine_function_to_call($function)
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
