<?php

use App\Mail;
use App\Mails\ResetPasswordMail;
use App\Validator;
use App\View;

include './view.php';
include './validator.php';
include './crud.php';
include './response.php';

function show_register(): never
{
    (new View('register'))->render();
}

function register_post(): never
{
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

function do_login(): never
{
    (new View('login'))->render();
}

function login_post(): never
{
    if ($_POST) {
        if (authenticate($_POST["email"], $_POST["password"])) {
            redirect_to("/?page=home");
        }

        put_error_message("Usuário ou/e senha incorretos");
    }

    redirect_to("/?page=login");
}

function do_not_found(): never
{
    (new View('not_found'))->render();
}

function do_validation(): never
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

function do_home(): never
{
    $user = auth_user();

    (new View('home'))
        ->withData([
            "field_name" => $user["name"],
            "field_email" => $user["email"],
        ])
        ->render();
}

function do_logout(): never
{
    logout();
    redirect_to("/?page=login");
}

function do_delete_account(): never
{
    crud_delete(auth_user()["email"]);
    put_flash_message("Conta deletada!");

    do_logout();
}

function do_forget_password(): never
{
    if ($_SERVER["REQUEST_METHOD"] == "GET") (new View("forget_password"))
        ->render();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = crud_restore($_POST["email"]);

        if (!$user) {
            put_error_message("E-mail não encontrado!");
            redirect_to("/?page=forget-password");
        }

        $resetPwdMail = new Mail(
            $user["email"],
            "Redefinição de Senha",
            new ResetPasswordMail(get_reset_password_token($user["email"])),
        );

        $resetPwdMail->send();

        put_flash_message("Enviamos um e-mail para redefinição de senha!");
    }

    redirect_to("/?page=login");
}

function do_change_password(): never
{
    $token_decrypt = base64_decode($_GET["token"]);
    $token_date = explode(":", $token_decrypt)[0];
    $token_email = explode(":", $token_decrypt)[1];
    if ($token_date != date("d-m-Y", strtotime("now"))) {
        put_error_message("Token expirado!");
        redirect_to("/?page=login");
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        (new View("change_password"))
            ->withData([
                "token" => $_GET["token"]
            ])
            ->render();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            (new Validator([
                "password" => ["min:10", "equals:password-confirm"]
            ], $_POST))->validate();

            $user = crud_restore($token_email);

            if (!$user) {
                put_error_message("Token inválido!");
                redirect_to("/?page=login");
            }

            crud_update($user["email"], ["password" => md5($_POST["password"])]);
            put_flash_message("Senha alterada! Faça login para continuar.");
        } catch (Exception $e) {
            put_error_message($e->getCode());
            reload();
        }
    }

    redirect_to("/?page=login");
}