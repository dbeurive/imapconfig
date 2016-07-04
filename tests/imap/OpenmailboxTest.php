<?php

namespace dbeurive\xmailconfigtest\imap;
use dbeurive\imapconfig\Openmailbox;

class OpenmailboxTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\imapconfigtest\SetUp;

    /** @var \dbeurive\imapconfig\Imap */
    private $__imap;
    private $__credential;

    public function setUp() {
        $this->__init();
        $this->__imap = new Openmailbox();
        $this->__credential = $this->__credentials['Openmailbox'];
    }

    public function testConnect() {
        // Throw an exception if an error is detected.
        print "Testing OpenMailbox(\"{$this->__credential['user']}\", \"{$this->__credential['password']}\")\n";
        $this->__imap->getImapStream($this->__credential['user'], $this->__credential['password']);
    }
}