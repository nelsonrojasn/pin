<?php

class LoginPage
{
	public function index()
    {
        load_view('login/index');
    }

	public function signin()
    {
        csrf_protect();

        $email = request_post('email', 'string');
        $password = request_post('password', 'string');

        // Buscamos al usuario por su login (usamos el campo email del formulario)
        $user = db_find_first("SELECT * FROM user WHERE login = :login", ['login' => $email]);

        if ($user && password_verify($user['salt'] . $password, $user['password_hash'])) {
            session_set('is_logged_in', true);
            session_set('user_id', $user['id']);
            session_set('company_id', $user['company_id']);
            session_set('flash', 'Bienvenido!');
            redirect_to('');
        }

        session_set('flash', 'Usuario o clave inválidos.');
        load_view('login/index');
    }

	public  function signout()
    {
        session_destroy();
        session_set('flash', 'Adios!');
        redirect_to('');
    }
}
