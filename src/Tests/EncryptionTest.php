<?php

namespace OpenEncryption\Tests;

use OpenEncryption\Encryption;

class EncryptionTest extends \PHPUnit_Framework_TestCase
{
    const USERNAME = 'test@example.com';
    const PASSWORD = 'test123';
    const SALT = 'hajs23hidsfbm923k';
    const HASH = '4EJpyA53CBka8y5GCiF3pg==';
    const INCORRECT_HASH='aR6EwTY35Bf4i5B2cey6nM==';

    public function testEncrypt()
    {
        $result = Encryption::encrypt(self::PASSWORD, self::SALT, self::USERNAME);
        $this->assertSame(self::HASH, $result);
    }

    public function testDecrypt()
    {
        $result = Encryption::decrypt(self::HASH, self::SALT, self::USERNAME);
        $this->assertSame(self::PASSWORD, $result);
    }

    public function testIncorrectHashDecrypt(){
        $result = Encryption::decrypt(self::INCORRECT_HASH, self::SALT, self::USERNAME);
        $this->assertNotSame(self::PASSWORD, $result);
    }
}
