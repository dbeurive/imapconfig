<?php

namespace dbeurive\xmailconfigtest\imap;
use dbeurive\imapconfig\Yahoo;

class YahooTest extends \PHPUnit_Framework_TestCase
{
    use \dbeurive\imapconfigtest\SetUp;

    /** @var \dbeurive\imapconfig\Imap */
    private $__imap;
    private $__credential;

    public function setUp() {
        $this->__init();
        $this->__imap = new Yahoo();
        $this->__credential = $this->__credentials['Yahoo'];
    }

    public function testConnect() {
        // Throw an exception if an error is detected.
        print "Testing Yahoo(\"{$this->__credential['user']}\", \"{$this->__credential['password']}\")\n";
        $this->__imap->getImapStream($this->__credential['user'], $this->__credential['password']);
    }
}