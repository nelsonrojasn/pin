<?php

function index()
{
	$template = new Template();
	$template->set("saludo", "Saludo interno");
	$template->render("default/index");
}

function redirigir()
{
	return redirect_to("page/show");
}
