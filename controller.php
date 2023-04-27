<?php

use App\Validator;
use App\View;

include './view.php';
include './validator.php';
include './crud.php';
include './response.php';

function show_register(): void
{
    (new View('register'))->render();
}

function register_post(): void
{
    if ($_POST) {
        try {
            $validator = new Validator([
                'email' => ['unique'],
                'password' => ['min:10'],
                'password-confirm' => ['min:10', 'equals:password'],
            ], $_POST);

            $validator->validate();

            unset($_POST["password-confirm"]);
            crud_create($_POST);

            put_flash_message("Cadastro Realizado! Lhe enviamos um e-mail para confirmação!");

            redirect_to(
                "http://localhost:8080/?page=login"
            );
        } catch (Exception $e) {
            put_error_message($e->getMessage());

            redirect_to(
                "http://localhost:8080/?page=register",
            );
        }
    }
}

function do_login(): void
{
    (new View('login'))->render();
}

function login_post(): void
{
    if ($_POST) {
        if (authenticate($_POST["email"], $_POST["password"])) {
            redirect_to("/?page=home");
        }

        put_error_message("Usuário ou/e senha incorretos");
    }

    redirect_to("/?page=login");
}

function do_not_found(): void
{
    (new View('not_found'))->render();
}

function do_validation(): void
{
    if ($_GET && $_GET["token"]) {
        $users = json_decode(
            file_get_contents(DATA_LOCATION), true
        );

        foreach ($users as $item) {
            if (gettype(decrypt_ssl($_GET["token"], $item["email"])) == 'string') {
                crud_update($item['email'], ["mail_validation" => true]);
            }
        }

        put_flash_message("E-mail confirmado! Faça login para continuar!");
    }

    redirect_to("http://localhost:8080/?page=login");
}

function do_home(): void
{
    (new View('home'))->render();
}