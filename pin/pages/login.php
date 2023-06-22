<?php

load_lib("session");
	
function signin()
{
	Session::set("is_logged_in", true);
	Session::set("flash", "Bienvenido!");
	return redirect_to("");
}

function signout()
{
	Session::destroy();
	return redirect_to("");
}

