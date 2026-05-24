<?php


function page_initializer()
{
	if (session_get("is_logged_in") !== true) {
		//ir a la pagina inicial
		session_set("flash", "Debe iniciar sesión para acceder al recurso *<i>page</i>*");
		redirect_to("");
	}
}

function show($slug = '')
{
	load_view("page/show", ["slug" => $slug]);
}

function edit()
{
	load_view("page/edit");
}

function update()
{
    csrf_protect();
}



