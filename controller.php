<?php

include './view.php';
include './crud.php';

function show_register()
{
    render_view('register');
}

function do_register()
{
    if ($_POST) {
        crud_create($_POST);
        header('Location: http://localhost:8080/?page=login');
    }
}

function do_login()
{
    render_view('login');
}

function do_not_found()
{
    render_view('not_found');
}
