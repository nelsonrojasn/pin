<?php

namespace App\Libs;

/**
 * Session
 * Clase para gestionar variables de sesión. Ideal para autenticación 
 * o carros de compra
 * @author nelson rojas
 */
class Session
{
	/**
	 * Permite obtener una entrada desde la variable $_SESSION
	 * de acuerdo a su clave $key 
	 * @param $key
	 * @return mixed
	 */
	public static function get($key)
	{
		if (empty($_SESSION[$key])) {
			return null;
		}

		return $_SESSION[$key];
	}

	/**
	 * Permite crear una entrada en la variable $_SESSION
	 * de acuerdo a su clave $key 
	 * @param $key
	 * @param mixed $value
	 * @return void
	 */
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Permite eliminar una entrada en la variable $_SESSION
	 * de acuerdo a su clave $key 
	 * @param $key
	 * @return void
	 */
	public static function delete($key)
	{
		unset($_SESSION[$key]);
	}

	/**
	 * Permite cerrar la sesión
	 */
	public static function destroy()
	{
		session_destroy();
	}
}
