<?php

use OpenEncryption\Encryption;

class EncryptionTest extends \PHPUnit_Framework_TestCase
{
    const USERNAME = 'test@example.com';
    const PASSWORD = 'test123';
    const SALT = 'hajs23hidsfbm923k';
    const HASH = 'hxas+7VCPPaJiLMPjWvUyA==';
    const INCORRECT_HASH = 'aR6EwTY35Bf4i5B2cey6nM==';
    const EMPTY_USERNAME = '';
    const EMPTY_USERNAME_HASH = '4Y9nTh7b4410hh+ha7ouqg==';
    const OWN_SECRET_KEY = 'mySecret';
    const HASH_BASED_ON_OWN_SECRET_KEY = 'H3gwRPkQTKivWcllQFeezA==';

    private $encryption;

    public function setUp()
    {
        $this->encryption = new Encryption();
    }

    public function testEncrypt()
    {
        $result = $this->encryption->encrypt(self::PASSWORD, self::SALT, self::USERNAME);
        $this->assertSame(self::HASH, $result);
    }

    public function testDecrypt()
    {
        $result = $this->encryption->decrypt(self::HASH, self::SALT, self::USERNAME);
        $this->assertSame(self::PASSWORD, $result);
    }

    public function testIncorrectHashDecrypt()
    {
        $result = $this->encryption->decrypt(self::INCORRECT_HASH, self::SALT, self::USERNAME);
        $this->assertNotSame(self::PASSWORD, $result);
    }

    public function testEncryptWithEmptyUsername()
    {
        $result = $this->encryption->encrypt(self::PASSWORD, self::SALT, self::EMPTY_USERNAME);
        $this->assertSame(self::EMPTY_USERNAME_HASH, $result);
    }

    public function testDecryptWithEmptyUsername()
    {
        $result = $this->encryption->decrypt(self::EMPTY_USERNAME_HASH, self::SALT, self::EMPTY_USERNAME);
        $this->assertSame(self::PASSWORD, $result);
    }

    public function testEncryptPassingSecretKeyAsConstructorParameter()
    {
        $encryption = new Encryption(self::OWN_SECRET_KEY);
        $result = $encryption->encrypt(self::PASSWORD, self::SALT, self::USERNAME);
        $this->assertSame(self::HASH_BASED_ON_OWN_SECRET_KEY, $result);
    }

    public function testDecryptPassingSecretKeyAsConstructorParameter()
    {
        $encryption = new Encryption(self::OWN_SECRET_KEY);
        $result = $encryption->decrypt(self::HASH_BASED_ON_OWN_SECRET_KEY, self::SALT, self::EMPTY_USERNAME);
        $this->assertSame(self::PASSWORD, $result);
    }
}
