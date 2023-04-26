<?php

function redirect_with_successfull_message(string $to_url, string $message = "Sucesso"): never
{
    ob_start();
    session_start();
    $_SESSION[FLASH_MESSAGE_SESSION_NAME] = [ $message ];

    header("Location: $to_url");

    ob_end_flush();
    session_write_close();

    exit;
}

function redirect_with_error_message(string $to_url, string $message = "Erro"): never
{
    ob_start();
    session_start();
    $_SESSION['error_message'] = [$message];

    header("Location: $to_url");

    ob_end_flush();
    session_write_close();

    exit;
}

function redirect_to(string $to_url): never
{
    header("Location: $to_url");
    exit();
}