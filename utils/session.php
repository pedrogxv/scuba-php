<?php

function put_flash_message(string $message): void
{
    $_SESSION[FLASH_MESSAGE_SESSION_NAME] = [ $message ];
    session_write_close();
}

function put_error_message(string $message): void
{
    $_SESSION[ERROR_MESSAGES_SESSION_NAME] = [ $message ];
    session_write_close();
}