<?php

namespace App\Mails;

class MailValidation implements MailView
{
    public function __construct(
        private readonly string $token,
    )
    {
    }

    public function render(): string
    {
        return '
            <!DOCTYPE html>
            <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <title>Validação de E-mail</title>
            </head>
            <body>
            <h1>Validação de E-mail</h1>
            <p>Olá,</p>
            <p>Para confirmar seu endereço de e-mail, por favor clique no link abaixo:</p>
            <a href="http://localhost:8080/?page=validate_mail&token=' . $this->token . '">Clique aqui para confirmar seu endereço de e-mail</a>
            <p>Se você não solicitou essa confirmação, por favor ignore este e-mail.</p>
            <p>Obrigado!</p>
            </body>
            </html>
        ';
    }
}