<?php


function page_initializer()
{
	if (Session::get("is_logged_in") !== true) {
		//ir a la pagina inicial
		Session::set("flash", "Debe iniciar sesión para acceder al recurso *<i>page</i>*");
		redirect_to("");
		return;
	}
}

function show($slug = '')
{
	$template = new Template();
	$template->set("slug", $slug);
	$template->render("page/show");
}

function edit()
{
	$template = new Template();
	$template->render("page/edit");	
}

function update()
{
	$template = new Template();
	Session::set("flash", "Página actualizada correctamente");
	$template->render("page/edit");	
}



