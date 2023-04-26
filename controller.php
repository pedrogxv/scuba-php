<?php

use App\View;

include './view.php';
include './crud.php';
include './response.php';

function show_register()
{
    View::render_view('register');
}

function do_register()
{
    if ($_POST) {
        crud_create($_POST['person']);

        redirect_with_successfull_message(
            "http://localhost:8080/?page=login",
            "Usuário Cadastrado"
        );
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
