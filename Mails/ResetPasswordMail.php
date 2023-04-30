<?php

namespace App\Mails;

class ResetPasswordMail implements MailView
{
    public function __construct(
        private readonly string $token,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return '
            <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Redefinição de senha</title>
              </head>
              <body>
                <h1>Redefinição de senha</h1>
                <p>Para redefinir sua senha, clique no link abaixo:</p>
                <a href="http://localhost:8080/?page=change-password&token=' . $this->token . '">Redefinir senha</a>
              </body>
            </html>
        ';
    }
}