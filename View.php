<?php

namespace App;

use DOMDocument;
use DOMXPath;

class View
{
    static public function render_view(string $template)
    {
        $content = file_get_contents(VIEW_FOLDER . "$template.view");

        $doc = new DOMDocument('1.0', 'UTF-8');

        $doc->loadHTML($content);

        self::render_success_message($doc);

        echo $doc->saveHTML();
    }

    static private function render_success_message(DOMDocument $document)
    {
        session_start();

        if (!isset($_SESSION)) return;
        if (!array_key_exists('flash_message', $_SESSION)) return;

        $fragment = $document->createDocumentFragment();
        foreach ($_SESSION['flash_message'] as $message) {
            $fragment->appendChild(
                $document->createElement('p', $message)
            );
        }

        $domX = new DOMXPath($document);
        // getting element by class name
        $success_div = $domX->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'mensagem-sucesso')]");
        $success_div[0]->appendChild($fragment);

        // remove a flash_message
        unset($_SESSION['flash_message']);
    }
}