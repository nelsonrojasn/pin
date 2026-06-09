<?php

class PagePage
{
	public function index()
	{
		load_view("page/index");
	}

	public function show($slug = '')
	{
		load_view("page/show", ["slug" => $slug]);
	}

	public function edit()
	{
		load_view("page/edit");
	}

	public function update()
	{
		csrf_protect();
	}
}



