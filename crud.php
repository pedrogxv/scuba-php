<?php

function crud_create($user) {
    $users = json_decode(
        file_get_contents("./data/users.json")
    );

    $users += $user;

    // persisting
    file_put_contents("./data/users.json", json_encode($users));
}
