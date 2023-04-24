<?php

include './controller.php';

if (!array_key_exists('page', $_GET)) do_login();
else if ($_GET['page'] == 'login') do_login();
else if ($_GET['page'] == 'register') do_register();
else
    do_not_found();
