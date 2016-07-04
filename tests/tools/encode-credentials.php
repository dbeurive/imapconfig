<?php

# Usage:
#
# php encode-credentials.php cr:crypt --help
# php encode-credentials.php cr:print --help
#
# cr:crypt: encrypt users and passwords, so it can be sent to GitHub.
#     php encode-credentials.php cr:crypt [--public-key-path=<...>] [--credentials-path=<...>]
#     Default: --public-key-path  = ~/.ssh/dev/key_pub.pem
#              --credentials-path = ~/.ssh/dev/credentials.php
#
# cr:print: print the users and passwords.
#     php encode-credentials.php cr:print [--private-key-path=<...>]
#     Default: --private-key-path  = ~/.ssh/dev/key_prv.pem


$path = ['..', 'bootstrap.php'];
$autoload = __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path);
require $autoload;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use dbeurive\Util\UtilData;

class Cypher extends Command {
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command
     */
    protected function configure() {
        $this->setName('cr:crypt')
            ->setDescription("Crypt the credentials")
            ->addOption('public-key-path', null, InputOption::VALUE_OPTIONAL, "Path to the RSA public key", getenv('HOME') . '/.ssh/dev/key_pub.pem')
            ->addOption('credentials-path', null, InputOption::VALUE_OPTIONAL, "Path to credentials to crypt", getenv('HOME') . '/.ssh/dev/credentials.php');
    }

    /**
     * This method is called by the Symfony's console class.
     * @see Symfony\Component\Console\Command\Command
     * @param InputInterface $input Input interface.
     * @param OutputInterface $output Output interface.
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        // Load the credentials.
        $credentialsPath = $input->getOption('credentials-path');
        if (! file_exists($credentialsPath)) {
            throw new \Exception("File \"$credentialsPath\" does not exist.");
        }
        $credentials = require $credentialsPath;

        // Load the public key
        $publicKeyPath = $input->getOption('public-key-path');
        $text = file_get_contents($publicKeyPath);
        if (false === $text) {
            throw new \Exception("Could not load the file that contains the public key ($publicKeyPath).");
        }

        $publicKey = openssl_pkey_get_public($text);
        if (false === $publicKey) {
            throw new \Exception("The given path does not contain a valid public key ($publicKeyPath).");
        }

        // Encode the credentials.
        $result = array();
        foreach ($credentials as $_service => $_config) {
            $encodedUser = null;
            $encodedPassword = null;
            $class = $_config['class'];
            $user = $_config['user'];
            $password = $_config['password'];

            if (false === openssl_public_encrypt($user, $encodedUser, $publicKey)) {
                throw new \Exception("An error occurred while encoding the text \"$user\".");
            }

            if (false === openssl_public_encrypt($password, $encodedPassword, $publicKey)) {
                throw new \Exception("An error occurred while encoding the text \"$password\".");
            }

            $result[$_service] = array(
                'class' => $class,
                'user' => bin2hex($encodedUser),
                'password' => bin2hex($encodedPassword)
            );
        }

        $targetFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'credentials.php';
        UtilData::to_callable_php_file($result, $targetFile);
    }

}

class Printer extends Command {
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command
     */
    protected function configure() {
        $this->setName('cr:print')
            ->setDescription("Print the credentials")
            ->addOption('private-key-path', null, InputOption::VALUE_OPTIONAL, "Path to the RSA private key", getenv('HOME') . '/.ssh/dev/key_prv.pem');
    }

    /**
     * This method is called by the Symfony's console class.
     * @see Symfony\Component\Console\Command\Command
     * @param InputInterface $input Input interface.
     * @param OutputInterface $output Output interface.
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        // Load the private key
        $privateKeyPath = $input->getOption('private-key-path');
        $text = file_get_contents($privateKeyPath);
        if (false === $text) {
            throw new \Exception("Could not load the file that contains the private key ($privateKeyPath).");
        }

        $privateKey = openssl_pkey_get_private($text);
        if (false === $privateKey) {
            throw new \Exception("The given path does not contain a valid private key ($privateKeyPath).");
        }

        $credentialsPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'credentials.php';
        $credentials = require $credentialsPath;
        if (false === $credentials) {
            throw new \Exception("File \"${$credentialsPath}\" does not exist or is corrupted.");
        }

        // Print the credentials.
        foreach ($credentials as $_service => $_config) {
            $encodedUser     = hex2bin($_config['user']);
            $encodedPassword = hex2bin($_config['password']);
            $user            = null;
            $password        = null;

            if (false === openssl_private_decrypt($encodedUser, $user, $privateKey)) {
                throw new \Exception("An error occurred while decoding the user.");
            }

            if (false === openssl_private_decrypt($encodedPassword, $password, $privateKey)) {
                throw new \Exception("An error occurred while decoding the password.");
            }

            print "${_service}:\n\tUser     = ${user}\n\tPassword = ${password}\n";
        }
    }
}


$application = new Application();
$application->setAutoExit(true);
$application->add(new Cypher());
$application->add(new Printer());
$application->run();

