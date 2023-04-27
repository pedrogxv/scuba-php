<?php

namespace App\Mails;

interface MailView
{
    /**
     * Render the view. (Only HTML supported)
     */
    public function render(): string;
}