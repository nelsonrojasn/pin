<?php

namespace App\Handlers;

use App\Libs\Session;

/**
 * LoginHandler
 * Handler para la autenticación
 */
class LoginHandler extends Handler
{
    public function handle(array $params = [])
    {
        $action = $_SERVER['REQUEST_METHOD'];

        if ($action === 'POST') {
            if (isset($params[0]) && $params[0] === 'signin') {
                $this->signin();
            } elseif (isset($params[0]) && $params[0] === 'signout') {
                $this->signout();
            }
        }

        if ($action === 'GET') {
            Session::set("is_logged_in", true);
            Session::set("flash", "Bienvenido!");
            $this->redirect("");
        }
    }

    

    private function signout()
    {
        Session::destroy();
        Session::set("flash", "Adios!");
        $this->redirect("");
    }

    private function signin()
    {
        Session::set("is_logged_in", true);
        Session::set("flash", "Bienvenido!");
        $this->redirect("");
    }

    private function redirect($path)
    {
        $url = PUBLIC_PATH . $path;
        header("Location: $url", true, 301);
    }
}
