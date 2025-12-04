<?php

namespace App\Handlers;

use App\Libs\Template;
use App\Libs\Session;

/**
 * PageHandler
 * Handler para la gestión de páginas
 */
class PageHandler extends Handler
{
    public function handle(array $params = [])
    {
        // Validar autenticación
        if (Session::get("is_logged_in") !== true) {
            Session::set("flash", "Debe iniciar sesión para acceder al recurso *<i>page</i>*");
            $this->redirect("");
            return;
        }

        // Determinar la acción basada en el método HTTP y parámetros
        $action = $_SERVER['REQUEST_METHOD'];
        $slug = $params[0] ?? '';

        if ($action === 'GET') {
            if (isset($params[0])) {
                // GET /page/show/{slug}
                $this->show($slug);
            } else {
                // GET /page/edit
                $this->edit();
            }
        } elseif ($action === 'POST') {
            // POST /page/update
            $this->update();
        }
    }

    private function show($slug = '')
    {
        $template = new Template();
        $template->set("slug", $slug);
        $template->render("page/show");
    }

    private function edit()
    {
        $template = new Template();
        $template->render("page/edit");	
    }

    private function update()
    {
        $template = new Template();
        Session::set("flash", "Página actualizada correctamente");
        $template->render("page/edit");	
    }

    private function redirect($path)
    {
        $url = PUBLIC_PATH . $path;
        header("Location: $url", true, 301);
    }
}
