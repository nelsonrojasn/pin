<?php

class DefaultPage
{
	public function index()
	{
		load_view("default/index", [
			"saludo" => "¡Bienvenido a Pin Zero!",
			"db_exists" => file_exists(DB_PATH),
			"setup_allowed" => defined('ALLOW_SETUP') && ALLOW_SETUP === true
		]);
	}
}