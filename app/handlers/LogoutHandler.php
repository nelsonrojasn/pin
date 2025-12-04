<?php

namespace App\Handlers;

use App\Libs\Session;

/**
 * LoginHandler
 * Handler para la autenticación
 */
class LogoutHandler extends Handler
{
    public function handle(array $params = [])
    {
        $action = $_SERVER['REQUEST_METHOD'];
        
        if ($action === 'GET') {
            Session::destroy();
            Session::set("flash", "Adios!");
            $this->redirect("");
        }
    }

    private function redirect($path)
    {
        $url = PUBLIC_PATH . $path;
        header("Location: $url", true, 301);
    }
}
