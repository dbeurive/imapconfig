<?php

namespace dbeurive\xmailconfigtest\imap;
use dbeurive\imapconfig\Gmail;

class GmailTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\imapconfigtest\SetUp;

    /** @var \dbeurive\imapconfig\Imap */
    private $__imap;
    private $__credential;

    public function setUp() {
        $this->__init();
        $this->__imap = new Gmail();
        $this->__credential = $this->__credentials['Gmail'];
    }

    public function testConnect() {
        // Throw an exception if an error is detected.
        print "Testing Gmail(\"{$this->__credential['user']}\", \"{$this->__credential['password']}\")\n";
        $this->__imap->getImapStream($this->__credential['user'], $this->__credential['password']);
    }
}