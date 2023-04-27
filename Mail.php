<?php

namespace App;

use App\Mails\MailView;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private readonly PHPMailer $PHPMailer;

    public function __construct(
        private readonly string $to,
        private readonly string $subject,
        private readonly MailView $view,
    )
    {
    }

    private function createMailerInstance(): void
    {
        $this->PHPMailer = new PHPMailer();
        $this->PHPMailer->isSMTP();
        $this->PHPMailer->Host = $_ENV["MAIL_HOST"];
        $this->PHPMailer->SMTPAuth = true;
        $this->PHPMailer->Port = $_ENV["MAIL_PORT"];
        $this->PHPMailer->Username = $_ENV["MAIL_USERNAME"];
        $this->PHPMailer->Password = $_ENV["MAIL_PASSWORD"];
    }

    /**
     * @throws Exception
     */
    private function mountMail(): void
    {
        $this->PHPMailer->Subject = $this->subject;
        $this->PHPMailer->addAddress($this->to);
        $this->PHPMailer->isHTML();
        $this->PHPMailer->CharSet = 'UTF-8';
        $this->PHPMailer->Body = $this->view->render();
    }

    public function send(): void
    {
        try {
            $this->createMailerInstance();
            $this->mountMail();

            $this->PHPMailer->send();
        } catch (Exception $e) {
            // @TODO : Tratar erro de uma forma melhor
            var_dump($e);
        }
    }
}