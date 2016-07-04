<?php

namespace dbeurive\imapconfig;

class Netcourrier extends Imap {
    public function __construct() {
        $this->setHost('imap.netcourrier.com')
            ->setCypher('tls');
    }
}