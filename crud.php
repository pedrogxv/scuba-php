<?php

use App\Mail;
use App\Mails\MailValidation;

include 'crypt.php';

function crud_create(array $user): void
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION)
    );

    $user['mail_validation'] = false;

    $users[] = $user;

    // persisting
    file_put_contents(DATA_LOCATION, json_encode($users));

    $mail = new Mail($user['email'], "Validação de e-mail", new MailValidation(
        encrypt_ssl($user['email'], $user['email'])
    ));

    $mail->send();
}
