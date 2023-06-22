<?php

load_config("database");
load_lib("db");
load_lib("session");

function page_initializer()
{
	if (Session::get("is_logged_in") !== true) {
		//ir a la pagina inicial
		Session::set("flash", "Debe iniciar sesión para acceder al recurso *<i>page</i>*");
		return redirect_to("");
	}
}

function show($slug)
{
	$users = Db::findAll("SELECT * FROM Usuario");
	load_view("page/show", ['users' => $users, 'slug' => $slug]);
}

function edit()
{
	$users = Db::findAll("SELECT * FROM Usuario");
	load_view("page/edit", ['users' => $users]);
}

