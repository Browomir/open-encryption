Open Encryption
===============
Simple password encryption class based on [OpenSSL](http://php.net/manual/en/book.openssl.php).

# Prerequisites

- PHP 5.4 or later

# Installation

The preferred way to install this class is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require browomir/open-encryption "dev-master"
```

or add

```js
// composer.json
{
    "require": {
        "browomir/open-encryption": "dev-master"
    }
}
```

to the require section of your `composer.json` file.


# Usage

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
[![Coverage Status](https://coveralls.io/repos/Browomir/open-encryption/badge.svg?branch=master&service=github)](https://coveralls.io/github/Browomir/open-encryption?branch=master)
[![Latest Stable Version](https://poser.pugx.org/browomir/open-encryption/v/stable)](https://packagist.org/packages/browomir/open-encryption) 
[![Latest Unstable Version](https://poser.pugx.org/browomir/open-encryption/v/unstable)](https://packagist.org/packages/browomir/open-encryption) 
[![Dependency Status](https://www.versioneye.com/user/projects/563e177f4d415e001b0000bf/badge.svg?style=flat)](https://www.versioneye.com/user/projects/563e177f4d415e001b0000bf)
[![Total Downloads](https://poser.pugx.org/browomir/open-encryption/downloads)](https://packagist.org/packages/browomir/open-encryption) 
[![License](https://poser.pugx.org/browomir/open-encryption/license)](https://packagist.org/packages/browomir/open-encryption)