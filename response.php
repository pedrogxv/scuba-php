<?php

function redirect_with_successfull_message(string $to_url, string $message = "Sucesso")
{
    ob_start();
    session_start();
    $_SESSION['flash_message'] = [$message];

    header("Location: $to_url");

    ob_end_flush();
    session_write_close();

    exit;
}