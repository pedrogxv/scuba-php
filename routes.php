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
    else if ($_GET['page'] == 'forget-password') do_forget_password();
    else if ($_GET['page'] == 'change-password') do_change_password();
    else
        do_not_found();
}

function auth_routes(): void
{
    if ($_GET['page'] == 'home') do_home();
    else if ($_GET['page'] == 'logout') do_logout();
    else if ($_GET['page'] == 'delete-account') do_delete_account();
    else
        do_not_found();
}

