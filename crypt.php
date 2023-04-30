<?php

function encrypt_ssl(string $data, string $passphrase): string
{
    return openssl_encrypt($data, "AES-256-CBC", $passphrase);
}

function decrypt_ssl(string $data, string $passphrase): string
{
    return openssl_decrypt($data, "AES-256-CBC", $passphrase);
}

function get_reset_password_token(string $email): string
{
    $today = date("d-m-Y", strtotime("today"));

    return base64_encode("$today:$email");
}