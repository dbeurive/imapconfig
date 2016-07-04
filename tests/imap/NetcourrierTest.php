<?php

namespace dbeurive\xmailconfigtest\imap;
use dbeurive\imapconfig\Netcourrier;

class NetcourrierTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\imapconfigtest\SetUp;

    /** @var \dbeurive\imapconfig\Imap */
    private $__imap;
    private $__credential;

    public function setUp() {
        $this->__init();
        $this->__imap = new Netcourrier();
        $this->__credential = $this->__credentials['Netcourrier'];
    }

    public function testConnect() {
        // Throw an exception if an error is detected.
        print "Testing Netcourrier(\"{$this->__credential['user']}\", \"{$this->__credential['password']}\")\n";
        $this->__imap->getImapStream($this->__credential['user'], $this->__credential['password']);
    }
}