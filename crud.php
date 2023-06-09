<?php

use App\Mail;
use App\Mails\MailValidation;

include 'crypt.php';

function crud_restore(string $email): ?array
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION), true
    );

    $user = null;

    foreach ($users as $item) {
        if ($item['email'] === $email) {
            $user = $item;
        }
    }

    return $user;
}

function crud_create(array $user): void
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION)
    );

    $user['mail_validation'] = false;
    $user["password"] = md5($user["password"]);

    $users[] = $user;

    // persisting
    file_put_contents(DATA_LOCATION, json_encode($users));

    $mail = new Mail($user['email'], "Validação de e-mail", new MailValidation(
        encrypt_ssl($user['email'], $user['email'])
    ));

    $mail->send();
}

function crud_update(string $email, array $newData): void
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION), true
    );

    foreach ($users as &$item) {
        if ($item['email'] === $email) {
            foreach ($newData as $field => $value) {
                if (isset($item[$field])) $item[$field] = $value;
            }
        }
    }

    file_put_contents(DATA_LOCATION, json_encode($users));
}

function crud_delete(string $email): void
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION), true
    );

    foreach ($users as $id => $item) {
        if ($item['email'] === $email) {
            array_splice($users, $id, 1);
        }
    }

    file_put_contents(DATA_LOCATION, json_encode($users));
}
