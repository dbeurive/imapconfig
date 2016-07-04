<?php

namespace dbeurive\imapconfig;

class Laposte extends Imap {
    public function __construct() {
        $this->setHost('imap.laposte.net')
            ->setCypher('ssl');
    }
}