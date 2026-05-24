<?php

function index()
{
	load_view("default/index", ["saludo" => "Saludo interno"]);
}

function redirigir()
{
	return redirect_to("page/show");
}
