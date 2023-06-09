<?php

/**
 * Put a successful message in the session bag.
 */
function put_flash_message(string $message): void
{
    $_SESSION[FLASH_MESSAGE_SESSION_NAME] = [ $message ];
}

/**
 * Put an error message in the session bag.
 */
function put_error_message(string $message): void
{
    $_SESSION[ERROR_MESSAGES_SESSION_NAME] = [ $message ];
}