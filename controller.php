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

function do_register(): void
{
    if ($_POST) {
        try {
            $validator = new Validator([
                'email' => ['unique'],
                'password' => ['min:10'],
                'password-confirm' => ['min:10', 'equals:password'],
            ], $_POST);

            $validator->validate();

            crud_create($_POST);

            redirect_with_successfull_message(
                "http://localhost:8080/?page=login",
                "Usuário Cadastrado"
            );
        } catch (Exception $e) {
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

function do_not_found(): void
{
    (new View('not_found'))->render();
}

function validate_mail(): void
{
    // @TODO : desacoplar validador de email
    if ($_GET && $_GET["token"]) {
        $users = json_decode(
            file_get_contents(DATA_LOCATION), true
        );

        foreach ($users as &$item) {
            if ($item['email'] === decrypt_ssl($_GET["token"], $item["email"])) {
                $item['mail_validation'] = true;
            }
        }

        file_put_contents(DATA_LOCATION, json_encode($users));
    }

    (new View('mail_confirmed'))->render();
}