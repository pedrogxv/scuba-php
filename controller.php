<?php

use App\View;

include './view.php';
include './crud.php';

function show_register()
{
    View::render_view('register');
}

function do_register()
{
    if ($_POST) {
        crud_create($_POST['person']);

        ob_start();
        session_start();
        $_SESSION['flash_message'] = ['Usuário Cadastrado'];

        header('Location: http://localhost:8080/?page=login');

        ob_end_flush();
        session_write_close();

        exit;
    }
}

function do_login()
{
    View::render_view('login');
}

function do_not_found()
{
    View::render_view('not_found');
}
