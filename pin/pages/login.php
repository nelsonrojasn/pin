<?php

function index()
{
    load_view('login/index');
}

function signin()
{
    csrf_protect();

    $email = request_post('email', 'string');
    $password = request_post('password', 'string');

    if ($email === 'admin' && $password === 'pin') {
        session_set('is_logged_in', true);
        session_set('flash', 'Bienvenido!');
        redirect_to('');
    }

    session_set('flash', 'Usuario o clave inválidos.');
    load_view('login/index');
}

function signout()
{
    session_destroy();
    session_set('flash', 'Adios!');
    redirect_to('');
}

