<?php
	
function signin()
{
	Session::set("is_logged_in", true);
	Session::set("flash", "Bienvenido!");
	redirect_to("");
	return;
}

function signout()
{
	Session::destroy();
	Session::set("flash", "Adios!");
	redirect_to("");
	return;
}

