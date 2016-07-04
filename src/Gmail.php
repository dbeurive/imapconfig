<?php

namespace dbeurive\imapconfig;

class Gmail extends Imap {
    public function __construct() {
        $this->setHost('imap.gmail.com')
            ->setCypher('ssl')
            ->needToAllowInsecureApp(true);

    }
}