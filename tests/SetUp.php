<?php

namespace dbeurive\imapconfigtest;

trait SetUp
{
    private $__credentials = false;

    private function __init() {

        if (false !== $this->__credentials) {
            return;
        }

        $privateKeyPath = getenv('HOME') . '/.ssh/dev/key_prv.pem';
        $credentialsPath = __DIR__ . DIRECTORY_SEPARATOR . 'credentials.php';
        $credentials = require $credentialsPath;

        $text = file_get_contents($privateKeyPath);
        if (false === $text) {
            throw new \Exception("Could not load the file that contains the private key ($privateKeyPath).");
        }

        $privateKey = openssl_pkey_get_private($text);
        if (false === $privateKey) {
            throw new \Exception("The given path does not contain a valid private key ($privateKeyPath).");
        }

        $this->__credentials = array();
        foreach ($credentials as $_service => $_config) {
            $encodedUser     = hex2bin($_config['user']);
            $encodedPassword = hex2bin($_config['password']);
            $class           = $_config['class'];
            $user            = null;
            $password        = null;

            if (false === openssl_private_decrypt($encodedUser, $user, $privateKey)) {
                throw new \Exception("An error occurred while decoding the text \"$encodedUser\".");
            }

            if (false === openssl_private_decrypt($encodedPassword, $password, $privateKey)) {
                throw new \Exception("An error occurred while encoding the text \"$encodedPassword\".");
            }

            $this->__credentials[$_service] = array(
                'class'    => $class,
                'user'     => $user,
                'password' => $password
            );
        }
    }

}