<?php

/**
 * This file implements the base class for all IMAP configuration classes.
 */

namespace dbeurive\imapconfig;

/**
 * Class Imap
 *
 * This class is the base class for all IMAP configuration classes.
 *
 * @package dbeurive\imapconfig
 */

class Imap {

    /**
     * @var string|false This property defines the encryption protocol used to secure communications between
     *      the IMAP client and the server. Value can be:
     *      *  ssl (for "Secure Sockets Layer").
     *      *  tls (for "Transport Layer Security").
     *
     *      SSL and TLS both provide a way to encrypt a communication channel between two computers
     *      (e.g. your computer and our server). TLS is the successor to SSL and the terms SSL and TLS
     *      are used interchangeably unless you're referring to a specific version of the protocol.
     */
    protected $__cypher = false;

    /**
     * @var string|false IP address or name of the host that runs the IMAP server.
     */
    protected $__host = false;

    /**
     * @var int|false TCP port number used by the IMAP server to listen for incoming connections.
     *      Typically:
     *      * If the cypher used to secure communications between the IMAP client and the server
     *        is SSL, then the required value is 993.
     *      * If the cypher used to secure communications between the IMAP client and the server
     *        is TLS, then the required value is 143.
     */
    protected $__port = false;

    /**
     * @var bool This property defines whether the service provider uses an additional protocol to secure the
     *      authentication or not (typically, GMAIL uses OAuth2. GMAIL pretends that IMAP clients that don't
     *      incorporate OAuth2 are insecure). These service providers require that the mailbox's owners configure their
     *      mailboxes so that access by "unsecured" clients is allowed.
     */
    protected $__needToAllowInsecureApp = false;

    /**
     * Set the cypher (SSL or TLS), and (if not already set) the default value for the TCP port used by the IMAP server
     * to listen to incoming connections.
     * Please note that:
     * * If the TCP port is already set, then this method will not change its value.
     * * The (default) value of the TCP port set by this method can be overwritten later by calling the method setPort().
     * @param string $inCypher Cypher to use. The value can be:
     *        * "ssl"
     *        * "tls"
     * @return $this
     * @throws \Exception
     */
    public function setCypher($inCypher) {
        $this->__cypher = strtolower($inCypher);
        if (($this->__cypher != 'ssl') && ($this->__cypher != 'tls')) {
            throw new \Exception("Unexpected cypher name \"${inCypher}\".");
        }
        if (false !== $this->__port) return $this;

        switch ($this->__cypher) {
            case 'ssl': $this->__port = 993; break;
            case 'tls': $this->__port = 143;
        }
        return $this;
    }

    /**
     * Set the value for the TCP port used by the IMAP server to listen to incoming connections.
     * @param int $inPort TCP port.
     * @return $this
     */
    public function setPort($inPort) {
        $this->__port = $inPort;
        return $this;
    }

    /**
     * Set the name, or the IP address, of the host that runs the IMAP server.
     * @param string $inHost Name, or IP address, of the host that runs the IMAP server.
     * @return $this
     */
    public function setHost($inHost) {
        $this->__host = $inHost;
        return $this;
    }

    /**
     * Return the name, or the IP address, of the host that runs the IMAP server.
     * @return string The name (or IP address).
     * @throws \Exception
     */
    public function host() {
        if (false === $this->__host) {
            throw new \Exception("You did not specify the host that runs the IMAP server");
        }
        return $this->__host;
    }

    /**
     * Return the TCP port number used by the IMAP server to listen for incoming connections.
     * @return int The TCP port number.
     * @throws \Exception
     */
    public function port() {
        if (false === $this->__port) {
            throw new \Exception("You did not specify the port number, nor the cypher.");
        }
        return $this->__port;
    }

    /**
     * Return the name of he cypher used to secure the communications between the IMAP client and the server.
     * @return false|string The name of he cypher used to secure the communications between the IMAP client and the server.
     *         If no cypher has been specified, then the method returns tha value false.
     */
    public function cypher() {
        return $this->__cypher;
    }

    /**
     * Without argument, this method indicates whether the service provider wants the user to configure his mailbox so
     * that it can be accessed by "unsecured" applications.
     * If an argument is given, then the method sets the service provider politic.*
     * @param null|bool $inNeeded The value can be:
     *        * null: the method indicates whether the service provider wants the user to configure his mailbox so
     *          that it can be accessed by "unsecured" applications.
     *        * true: tell the class that the service provider wants the user to configure his mailbox so that it can be
     *          accessed by "unsecured" applications.
     *        * false: tell the class that the service provider does not require the user to configure his mailbox so that
     *          it can be accessed by "unsecured" applications.
     * @return bool|$this
     *     * If the method was called without argument, then it return a boolean.
     *       If true, then the service provider wants the user to configure his mailbox so that it can be accessed by
     *       "unsecured" applications.
     *     * Otherwise, it returns $this.
     */
    public function needToAllowInsecureApp($inNeeded=null) {
        if (is_null($inNeeded)) {
            return $this->__needToAllowInsecureApp;
        }
        $this->__needToAllowInsecureApp = $inNeeded;
        return $this;
    }

    /**
     * Return a string that represents the parameter "$mailbox" used within the function "imap_open()".
     * @return string The string that represents the parameter "$mailbox" used within the function "imap_open()".
     */
    public function mailbox() {
        return '{' . $this->host() . ':' . $this->port() . '/imap' .
        ($this->cypher() ? "/{$this->__cypher}" : '') .
        '}INBOX';
    }

    /**
     * Return an IMAP stream has returned by the function "imap_open()".
     * @param string $inUser Mailbox's user.
     * @param string $inPassword Password.
     * @param bool $inThrowExceptionOnFailure This flag defines whether or not the method should throw an exception if
     *        an error occurred. If true, then the method will throw an exception.
     * @return resource The method returns an IMAP stream has returned by the function "imap_open()".
     * @throws \Exception
     */
    public function getImapStream($inUser, $inPassword, $inThrowExceptionOnFailure=true) {
        $mbox = imap_open($this->mailbox(), $inUser, $inPassword);
        if (false === $mbox && $inThrowExceptionOnFailure) {
            $message = "Can not open IMAP connection to \"{$this->__host}\", on port \"{$this->__port}\"" .
                ($this->__cypher ? " using \"{$this->__cypher}\"" : '') . '.';
            if ($this->__needToAllowInsecureApp) {
                $message .= " Did you configure your email account so that insecure applications can access it? If not, then log to \"" . get_class($this) . "\" and configure your email account so that insecure applications to access it!";
            }
            throw new \Exception($message);
        }
        return $mbox;
    }
}