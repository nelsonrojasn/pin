<?php
	
function signin()
{
	Session::set("is_logged_in", true);
	Session::set("flash", "Bienvenido!");
	return redirect_to("");
}

function signout()
{
	Session::destroy();
	Session::set("flash", "Adios!");
	return redirect_to("");
}

