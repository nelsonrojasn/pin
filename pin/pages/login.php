<?php

load_lib("session");
	
function signin()
{
	Session::set("is_logged_in", true);
	Session::set("flash", "Bienvenido!");
	redirect_to("");
}

function signout()
{
	Session::destroy();
	redirect_to("");
}

