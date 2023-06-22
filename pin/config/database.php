<?php

define('DATABASE_DSN', "mysql:host=127.0.0.1;dbname=sistemadb;charset=utf8"); 
define('DATABASE_USER', "nelson"); 
define('DATABASE_PASSWORD', "secret"); 
define('DATABASE_PARAMETERS', [
	PDO::ATTR_PERSISTENT => true, 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]); 