<?php

namespace App\Handlers;

use Pin\Libs\Template;
use Pin\Libs\Session;

/**
 * DefaultHandler
 * Handler para la página de inicio
 */
class DefaultHandler extends Handler
{
    public function handle(array $params = [])
    {
        $template = new Template();
        $template->set("saludo", "Saludo interno");
        $template->render("default/index");
    }
}
