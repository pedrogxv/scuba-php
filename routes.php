<?php

include './controller.php';

if (!array_key_exists('page', $_GET)) do_login();
else if ($_GET['page'] == 'login') do_login();
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] == 'register') show_register();
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] == 'validate_mail') validate_mail();
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] == 'register') do_register();
else
    do_not_found();
