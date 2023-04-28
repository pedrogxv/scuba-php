<?php

namespace App;

use DOMDocument;
use DOMElement;
use DOMXPath;

class View
{
    private readonly DOMXPath $xPath;

    public function __construct(
        private readonly string      $template,
        private readonly DOMDocument $document = new DOMDocument('1.0', 'UTF-8'),
    )
    {
        $this->document->loadHTML(
            file_get_contents($this->getTemplateFilePath())
        );

        $this->xPath = new DOMXPath($this->document);
    }

    public function withData(array $data): View
    {
        foreach ($data as $name => $value) {
            $nodes = $this->xPath->query('//text()[contains(., "{{' . $name . '}}")]');

            if (!$nodes) continue;

            foreach ($nodes as $node) {
                $node->nodeValue = str_replace('{{' . $name . '}}', $value, $node->nodeValue);
            }
        }

        $this->document->saveHTML();

        return $this;
    }

    public function render(): void
    {
        self::render_success_message();
        self::render_form_error_messages();
        self::render_error_messages();

        echo $this->document->saveHTML();
    }

    private function getTemplateFilePath(): bool|string
    {
        return VIEW_FOLDER . "$this->template.view";
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

        // getting element by class name
        $success_div = $this->xPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'mensagem-sucesso')]");
        if (!$success_div[0]) return;

        $success_div[0]->appendChild($fragment);

        // remove a flash_message
        unset($_SESSION[FLASH_MESSAGE_SESSION_NAME]);
    }

    private function render_form_error_messages(): void
    {
        if (!isset($_SESSION) || empty($_SESSION[ERROR_MESSAGES_SESSION_NAME])) return;

        foreach ($_SESSION[ERROR_MESSAGES_SESSION_NAME] as $id => $error) {
            if (gettype($error) != "array") continue;

            $field = key($error);
            $message = $error[key($error)];

            // getting the input related to the issue
            $inputElement = $this->xPath->evaluate("//input[@name=\"$field\"]")->item(0);

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

    private function render_error_messages(): void
    {
        if (!isset($_SESSION)) return;
        if (!array_key_exists(ERROR_MESSAGES_SESSION_NAME, $_SESSION)) return;

        // getting element by class name
        $error_node = $this->xPath
            ->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'mensagem-erro')]")
            ->item(0);

        foreach ($_SESSION[ERROR_MESSAGES_SESSION_NAME] as $message) {
            $error_node->textContent .= $message . "\n";
        }

        unset($_SESSION[ERROR_MESSAGES_SESSION_NAME]);
    }
}