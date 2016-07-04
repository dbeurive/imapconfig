<?php

namespace dbeurive\xmailconfigtest\imap;
use dbeurive\imapconfig\Outlook;

class OutlookTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\imapconfigtest\SetUp;

    /** @var \dbeurive\imapconfig\Imap */
    private $__imap;
    private $__credential;

    public function setUp() {
        $this->__init();
        $this->__imap = new Outlook();
        $this->__credential = $this->__credentials['Outlook'];
    }

    public function testConnect() {
        // Throw an exception if an error is detected.
        print "Testing Outlook(\"{$this->__credential['user']}\", \"{$this->__credential['password']}\")\n";
        $this->__imap->getImapStream($this->__credential['user'], $this->__credential['password']);
    }
}