<?php

/**
 * Config
 * Clase para obtener la configuración de la base de datos
 * @author nelson rojas
 */
class Config
{
	private static $_db_config = [
		'dsn' => "mysql:host=127.0.0.1;dbname=app;charset=utf8",
		'user' => "nelson",
		'password' => "s3cret",
		'parameters' => [
			PDO::ATTR_PERSISTENT => true, 
		    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		]
	];

	public static function getDbConfig()
	{
		return self::$_db_config;
	}

}