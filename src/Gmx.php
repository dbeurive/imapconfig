<?php

namespace dbeurive\imapconfig;

class Gmx extends Imap {
    public function __construct() {
        $this->setHost('imap.gmx.com')
            ->setCypher('ssl');
    }
}