<?php

include './view.php';

function do_register() {
    var_dump('eowgmeo');
    return render_view('register');
}

function do_login() {
    return render_view('login');
}

function do_not_found() {
    return render_view('not_found');
}
