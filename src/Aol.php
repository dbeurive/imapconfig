<?php

namespace dbeurive\imapconfig;


class Aol extends Imap {
    public function __construct() {
        $this->setHost('imap.aol.com')
            ->setCypher('ssl');
    }
}