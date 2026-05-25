<?php

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



