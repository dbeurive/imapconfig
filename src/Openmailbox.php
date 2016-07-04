<?php

namespace dbeurive\imapconfig;

class Openmailbox extends Imap {
    public function __construct() {
        $this->setHost('imap.openmailbox.org')
            ->setCypher('ssl');
    }
}
