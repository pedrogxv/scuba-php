<?php

namespace App;

use DOMDocument;
use DOMElement;
use DOMXPath;

class View
{
    public function __construct(
        private readonly string $template,
        private readonly DOMDocument $document = new DOMDocument('1.0', 'UTF-8'),
    )
    {
        session_start();
    }

    public function render(): void
    {
        $content = file_get_contents(VIEW_FOLDER . "$this->template.view");

        $this->document->loadHTML($content);

        self::render_success_message();
        self::render_errors_messages();

        echo $this->document->saveHTML();
    }

    private function render_success_message(): void
    {
        if (!isset($_SESSION)) return;
        if (!array_key_exists(FLASH_MESSAGE_SESSION_NAME, $_SESSION)) return;

        $fragment = $this->document->createDocumentFragment();
        foreach ($_SESSION[FLASH_MESSAGE_SESSION_NAME] as $message) {
            $fragment->appendChild(
                $this->document->createElement('p', $message)
            );
        }

        $domX = new DOMXPath($this->document);
        // getting element by class name
        $success_div = $domX->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'mensagem-sucesso')]");
        $success_div[0]->appendChild($fragment);

        // remove a flash_message
        unset($_SESSION[FLASH_MESSAGE_SESSION_NAME]);
    }

    private function render_errors_messages(): void
    {
        if (!isset($_SESSION) || empty($_SESSION[ERROR_MESSAGES_SESSION_NAME])) return;

        $xpath = new DOMXPath($this->document);

        foreach ($_SESSION[ERROR_MESSAGES_SESSION_NAME] as $id => $error) {
            if (gettype($error) != "array") continue;

            $field = key($error);
            $message = $error[key($error)];

            // getting the input related to the issue
            $inputElement = $xpath->evaluate("//input[@name=\"$field\"]")->item(0);

            if ($inputElement instanceof DOMElement) {
                // getting the span tag that will receive the error message
                $nextSibling = $inputElement->nextElementSibling;

                if ($nextSibling instanceof DOMElement) {
                    // Check if <span> contains class "mensagem-erro"
                    if ($nextSibling->getAttribute('class') === 'mensagem-erro') {
                        $nextSibling->textContent = $message;
                        unset($_SESSION[ERROR_MESSAGES_SESSION_NAME][$id]);
                    }
                }
            }
        }
    }
}