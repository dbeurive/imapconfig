<?php

namespace dbeurive\imapconfig;

class Newmanity extends Imap {
    public function __construct() {
        $this->setHost('webmail.ntymail.com')
            ->setCypher('ssl');
    }
}