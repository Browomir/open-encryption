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

$encryption = new Encryption(); // you can pass secret key as constructor parameter
                                // e.g. $encryption = new Encryption('mySecret');
$encrypted = $encryption->encrypt($password, $salt, $username);
$decrypted = $encryption->decrypt($encrypted, $salt, $username);

echo 'Encrypted: ' . $encrypted;
echo '<br>';
echo 'Decrypted: ' . $decrypted;

```

Additional:

 - if you don't want use default cipher method (AES-256-CBC) you can set your own:
 
```php

$cipher = new Cipher('AES-128-ECB');
$encryption->setCipher($cipher);

```

- you can check if chosen cipher is supported and/or is strong:

```php

if ($cipher->isSupported() && $cipher->isCryptoStrong()) {
    echo 'Your cipher is good!';
} else {
    echo 'You should chose other cipher!';
}

```

- you can get list of all supported cipher by:

```php

$cipher->getSupportedCiphers();

```




[![Build Status](https://travis-ci.org/Browomir/open-encryption.svg)](https://travis-ci.org/Browomir/open-encryption)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Browomir/open-encryption/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Browomir/open-encryption/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Browomir/open-encryption/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Browomir/open-encryption/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/browomir/open-encryption/v/stable)](https://packagist.org/packages/browomir/open-encryption) 
[![Latest Unstable Version](https://poser.pugx.org/browomir/open-encryption/v/unstable)](https://packagist.org/packages/browomir/open-encryption) 
[![Dependency Status](https://www.versioneye.com/user/projects/563e177f4d415e001b0000bf/badge.svg?style=flat)](https://www.versioneye.com/user/projects/563e177f4d415e001b0000bf)
[![Total Downloads](https://poser.pugx.org/browomir/open-encryption/downloads)](https://packagist.org/packages/browomir/open-encryption) 
[![License](https://poser.pugx.org/browomir/open-encryption/license)](https://packagist.org/packages/browomir/open-encryption)