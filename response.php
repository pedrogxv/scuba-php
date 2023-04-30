<?php

function redirect_to(string $to_url): never
{
    header("Location: $to_url");
    exit();
}

function reload(): never
{
    header("Refresh: 0");
    die();
}