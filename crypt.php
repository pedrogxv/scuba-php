<?php

function encrypt_ssl(string $data, string $passphrase): string
{
    return openssl_encrypt($data, "AES-256-CBC", $passphrase);
}

function decrypt_ssl(string $data, string $passphrase): string
{
    return openssl_decrypt($data, "AES-256-CBC", $passphrase);
}
