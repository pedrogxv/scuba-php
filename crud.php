<?php

function crud_create(array $user)
{
    $users = json_decode(
        file_get_contents(DATA_LOCATION)
    );

    $users[] = $user;

    // persisting
    file_put_contents(DATA_LOCATION, json_encode($users));
}
