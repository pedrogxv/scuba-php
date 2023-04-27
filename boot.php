<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include 'utils/session.php';
include 'config.php';
include 'routes.php';
include 'auth.php';

if (auth_user()) {
    auth_routes();
} else {
    guest_routes();
}

