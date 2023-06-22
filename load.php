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
		return;		
	}
}

function load_lib(string $lib)
{
	load_file($lib, ".php", PIN_PATH . 'libs' . DS, "Librería");
}

function load_config(string $config)
{
	load_file($config, ".php", PIN_PATH . 'config' . DS, "Configuración");
}


function load_helper(string $helper)
{
	load_file($helper, ".php", PIN_PATH . 'helpers' . DS, "Helper");
}

function load_view(string $view, array $parameters = null)
{
	load_file($view, ".phtml", PIN_PATH . 'views' . DS, "Vista", $parameters);
}

function load_partial(string $partial, array $parameters = null)
{
	load_file($partial, ".phtml", PIN_PATH . 'partials'. DS , "Parcial", $parameters);		
}

function redirect_to(string $url)
{
	$url = PUBLIC_PATH . $url;
	header("Location: $url", 301);
}


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
        
		load_partial("templates/header");
        echo "<h1>Fatal error</h1>";
        echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
        echo "<p>Message: '" . $exception->getMessage() . "'</p>";
        echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
        echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
		load_partial("templates/footer");
    }
}


function load_page_from_url($url)
{
	$content = explode('/', $url);
    //quitar el elemento inicial vacio
    array_shift($content); 
	
	$page = !empty($content[0]) ? trim($content[0]) : 'default';
	array_shift($content); 
	
	if (file_exists(PIN_PATH . 'pages' . DS . $page . '.php')) {
		require PIN_PATH . 'pages' . DS . $page . '.php';
	} else {
		throw (new Exception("La página <b>$page</b> no existe!"));
		return;
	}
	
	$function_to_be_load = !empty($content[0]) ? trim($content[0]) : 'index';
	array_shift($content); 

	if (function_exists("page_initializer")) {
		call_user_func("page_initializer");
	}
	
	if (function_exists($function_to_be_load)) {
		call_user_func($function_to_be_load, $content);
	} else {
		throw (new Exception("La función <b>$function_to_be_load</b> no existe en la página <b>$page</b>!"));
		return;
	}
    
}