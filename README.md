Open Encryption
=========
Simple password encryption class based on [OpenSSL](http://php.net/manual/en/book.openssl.php).
**Requires PHP 5.4 or newer.**

Installation
------------

The preferred way to install this class is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require browomir/open-encryption "dev-master"
```

or add

```
"browomir/open-encryption": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

This simple example show how you can use this class:

```php

require_once 'vendor/autoload.php';

use OpenEncryption\Encryption;

$username = 'test@example.com';
$password = 'test123';
$salt = 'somesalt';

$encrypted = Encryption::encrypt($password, $salt, $username);
$decrypted = Encryption::decrypt($encrypted, $salt, $username);

echo 'Encrypted: ' . $encrypted;
echo '<br>';
echo 'Decrypted: ' . $decrypted;

```

[![Build Status](https://travis-ci.org/Browomir/open-encryption.svg)](https://travis-ci.org/Browomir/open-encryption)
[![Latest Stable Version](https://poser.pugx.org/browomir/open-encryption/v/stable)](https://packagist.org/packages/browomir/open-encryption) 
[![Total Downloads](https://poser.pugx.org/browomir/open-encryption/downloads)](https://packagist.org/packages/browomir/open-encryption) 
[![Latest Unstable Version](https://poser.pugx.org/browomir/open-encryption/v/unstable)](https://packagist.org/packages/browomir/open-encryption) 
[![License](https://poser.pugx.org/browomir/open-encryption/license)](https://packagist.org/packages/browomir/open-encryption)