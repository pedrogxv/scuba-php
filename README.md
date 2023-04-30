# Scuba-PHP 

*Projeto desenvolvido para o desafio "7 Days of Code" da Alura no tópico "PHP".*

### Objetivo

Refatorar um código procedural e muito acoplado em algo orientado a objetos
e com menos acoplamento.

Algumas referências de objetivo final são: Laravel, Symfony, etc.

### Documentação

- #### Views

###### Renderização

Todas as views do projeto estão contidas na pasta `view` e tem
`.view` como extensão.

Para renderizar uma view use a classe View, exemplo:

```php
$view = new View("home");
$view->render();
```

Este código irá renderizar o arquivo `view/home.view` no navegador do cliente.

###### Renderização com Dados

Para renderizar dados em uma view basta usar a função `withData(array $data)`
antes de chamar a renderização, exemplo:

```php
$view = new View("home");
$view
    ->withData([
        "username" => "Pedro"
    ])
    ->render();
```

Isso fara a substituição dos dados contidos na view, que devem estar representados
da seguinte forma: (entre colchetes)

```html
<span>{{username}}</span>
```

Resultado:

```html
<span>Pedro</span>
```

- #### Validadores

Quando estamos lidando com requisições de formulário, geralmente
desejamos validar os campos e valores recebidos, o __SCUBA__ tem
métodos que agilizam está função.

Para iniciar uma validação, instancie a classe de Validadores com os
parâmetros requisitados.

```php
$validador = new \App\Validator([
    "password" => ['min:10']
], $_POST);

$validador->validate();
```

Note que o primeiro parâmetro é um array que contém chaves que se referem
as chaves do segundo parâmetro, e o valor dessas chaves é outro array que
se refere aos tipos de validação, os tipos de validação disponíveis são:

- min:value - Checa se a string tem no mínimo `value` caracteres.
- min:value - Checa se a string tem no mínimo `value` caracteres.
- unique - Valida se o valor do campo é único na base de dados.

###### Tratamento de erros do Validador

Caso alguma regra do Validador não seja validado ele irá disparar um
`ValidadorException` que pode ser tratado por blocos `try...catch`, o
erro também será colocado na 'bag' de erros da sessão para ser renderizado
na view.

- #### E-mails

###### Configurações

Antes de enviar e-mails é necessário configurar o `.env` para os valores contidos em
`.env.example`. Caso não tenha o arquivo `.env` no seu projeto, copie o arquivo `.env.example`
e seus valores.

###### Criando e-mails

Cada e-mail da aplicação deve estar dentro da pasta `Mails/` e deve implementar
a interface `MailView`.

Exemplo de um e-mail:

```php
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
            <a href="http://localhost:8080/?page=mail-validation&token=' . $this->token . '">Clique aqui para confirmar seu endereço de e-mail</a>
            <p>Se você não solicitou essa confirmação, por favor ignore este e-mail.</p>
            <p>Obrigado!</p>
            </body>
            </html>
        ';
    }
}
```

A função `render()` deve retornar uma string que representa o html
que será renderizado no envio do e-mail.

###### Envio de e-mail

Após a criação de um e-mail como classe, você pode enviá-lo da usando a classe `Mail`:

```php
$mail = new Mail(
    "exemplo@exemplo.com",
    "Validação de e-mail",
    new MailValidation("123"),
);

$mail->send();
```

A classe `Mail` recebe três argumentos, em ordem:

- E-mail de destino
- Assunto do e-mail
- Classe `MailView` que terá o html renderizado