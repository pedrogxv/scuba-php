<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ValidatorException extends Exception
{
    public function __construct(
        string                  $message = "",
        int                     $code = 0,
        ?Throwable              $previous = null,
        private readonly string $field = "",
    )
    {
        parent::__construct($message, $code, $previous);

        if (!empty($this->field)) {
            session_start();
            $_SESSION[ERROR_MESSAGES_SESSION_NAME][] = [
                $this->field => $message
            ];
            session_write_close();
        }
    }

    public function getFieldName(): string
    {
        return $this->field;
    }
}