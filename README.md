Open Encryption
=========
Simple users password encryption class based on [OpenSSL](http://php.net/manual/en/book.openssl.php)

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