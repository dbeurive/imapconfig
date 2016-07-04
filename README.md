# Description

This package contains IMAP configurations for a list of email service providers. This list contains the following
providers:
 
* AOL
* Gmail
* Gmx
* LaPoste
* NetCourrier
* NewManity
* OpenMailbox
* Outlook
* Yahoo

> See https://support.mozilla.org/fr/kb/parametres-configuration-principaux-fournisseurs-adresses

# Installation

From the command line:

    composer require dbeurive/imapconfig
    
Or, within your file `composer.json`:
 
    "require": {
        "dbeurive/imapconfig": "*"
    }

# Usage

Connecting to Gamil:

```php
use dbeurive\imapconfig\Gmail;

$user       = 'YourGmailUser';
$password   = 'YourGmailPassword';
$imapConf   = new Gmail();
$imapStream = $imapConf->getImapStream($user, $password);
```

Or

```php
use dbeurive\imapconfig\Gmail;

$user       = 'YourGmailUser';
$password   = 'YourGmailPassword';
$imapConf   = new Gmail();
$imapStream = imap_open($imapConf->mailbox(), $user, $password);
```

# Classes

| Service             | Class                            | URL                             |
|---------------------|----------------------------------|---------------------------------|
| AOL                 | dbeurive\imapconfig\Aol          | https://www.aol.com             |
| Gmail               | dbeurive\imapconfig\Gmail        | https://mail.google.com/        |
| GMX                 | dbeurive\imapconfig\Gmx          | http://www.gmx.com/             |
| LaPoste             | dbeurive\imapconfig\Laposte      | https://www.laposte.net/        |
| NetCourrier / Net-C | dbeurive\imapconfig\Netcourrier  | https://www.netcourrier.com/    |
| NewManity           | dbeurive\imapconfig\Newmanity    | https://www.newmanity.com/      |
| OpenMailbox         | dbeurive\imapconfig\Openmailbox  | https://www.openmailbox.org/    |
| Outlook             | dbeurive\imapconfig\Outlook      | https://www.microsoft.com/      |
| Yahoo               | dbeurive\imapconfig\Yahoo        | https://mail.yahoo.com/         |

# API Overview

## Getting the configuration parameters

| Method                   | Description                                                                                                                                        |
|--------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| host()                   | Return the name, or the IP address, of the host that runs the IMAP server.                                                                         |
| port()                   | Return the TCP port number used by the IMAP server to listen for incoming connections.                                                             |
| cypher()                 | Return the name of he cypher used to secure the communications between the IMAP client and the server ("ssl" or "tls").                            |
| needToAllowInsecureApp() | This method indicates whether the service provider wants the user to configure his mailbox so that it can be accessed by "unsecured" applications. |

## Connecting to the IMAP server

| Method           | Description                                                                      |
|------------------|----------------------------------------------------------------------------------|
| getImapStream()  | Open an IMAP stream, as returned by the function `imap_open()`.                  | 
| mailbox()        | Return the mailbox's specification (as used within the function `imap_open()`).  |

# API

See the file [Imap.php](https://github.com/dbeurive/imapconfig/blob/master/src/Imap.php) for details.

