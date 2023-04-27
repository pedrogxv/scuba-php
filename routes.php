<?php

include './controller.php';

function guest_routes(): void
{
    if (!array_key_exists('page', $_GET)) do_login();
    else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] == 'login') do_login();
    else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] == 'register') show_register();
    else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] == 'mail-validation') do_validation();
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] == 'register') register_post();
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] == 'login') login_post();
    else
        do_not_found();
}

function auth_routes(): void
{
    if ($_GET['page'] == 'home') do_home();
    else
        do_not_found();
}

