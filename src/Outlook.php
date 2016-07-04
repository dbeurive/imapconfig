<?php

namespace dbeurive\imapconfig;

class Outlook extends Imap {
    public function __construct() {
        $this->setHost('imap-mail.Outlook.com')
            ->setCypher('ssl');
    }
}