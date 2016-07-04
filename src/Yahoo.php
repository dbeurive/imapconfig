<?php

namespace dbeurive\imapconfig;

class Yahoo extends Imap {
    public function __construct() {
        $this->setHost('imap.mail.yahoo.com')
            ->setCypher('ssl')
            ->needToAllowInsecureApp(true);

    }
}