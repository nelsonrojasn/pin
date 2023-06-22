<?php

function index()
{
	load_view("default/index");
}

function redirigir()
{
	return redirect_to("page/show");
}