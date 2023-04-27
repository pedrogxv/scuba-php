<?php

function authenticate(string $email, string $password): bool
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION), true
    );
    var_dump(md5($password));

    foreach ($users as $item) {
        if ($item['email'] === $email && $item["password"] === md5($password)) {
            
            $_SESSION["logged_user"] = $item;
            session_write_close();
            return true;
        }
    }

    return false;
}

function auth_user(): null|array
{
    return $_SESSION['logged_user'] ?? null;
}