<?php

namespace dbeurive\xmailconfigtest\imap;
use dbeurive\imapconfig\Laposte;

class LaposteTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\imapconfigtest\SetUp;

    /** @var \dbeurive\imapconfig\Imap */
    private $__imap;
    private $__credential;

    public function setUp() {
        $this->__init();
        $this->__imap = new Laposte();
        $this->__credential = $this->__credentials['Laposte'];
    }

    public function testConnect() {
        // Throw an exception if an error is detected.
        print "Testing LaPoste(\"{$this->__credential['user']}\", \"{$this->__credential['password']}\")\n";
        $this->__imap->getImapStream($this->__credential['user'], $this->__credential['password']);
    }
}