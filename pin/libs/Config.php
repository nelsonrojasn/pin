<?php

namespace Pin\Libs;
use PDO;

/**
 * Config
 * Clase para obtener la configuración de la base de datos
 * @author nelson rojas
 */
class Config
{
	private static $_db_config = [
		'dsn' => "mysql:host=DB_HOST;dbname=DB_NAME;charset=utf8",
		'user' => "USER_NAME",
		'password' => "USER_PASSWORD",
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