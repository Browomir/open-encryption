<?php

use OpenEncryption\Encryption;
use OpenEncryption\Cipher;

class EncryptionTest extends \PHPUnit_Framework_TestCase
{
    const USERNAME = 'test@example.com';
    const PASSWORD = 'test123';
    const SALT = 'hajs23hidsfbm923k';
    const OWN_SECRET_KEY = 'mySecret';
    const WEAK_CIPHER_METHOD = 'AES-128-ECB';
    const INCORRECT_CIPHER_METHOD = 'some-incorrect-cipher';
    const INCORRECT_HASH = 'aR6EwTY35Bf4i5B2cey6nM==';
    const HASH_WITHOUT_PARAMETERS = '3jQ4QXcHt3zyMFyFZoXQTw==';
    const NOT_SUPPORTED_CIPHER_METHOD = 'id-aes128-GCM';

    private $encryption;
    private $ownSecretKeyEncryption;
    private $weakCipher;
    private $environmentVersion;
    private $excludedEnvironments;

    public function setUp()
    {
        $this->encryption = new Encryption();
        $this->weakCipher = new Cipher(self::WEAK_CIPHER_METHOD);
        $this->encryption->setCipher($this->weakCipher);
        $this->ownSecretKeyEncryption = new Encryption(self::OWN_SECRET_KEY);
        $this->ownSecretKeyEncryption->setCipher($this->weakCipher);
        $this->environmentVersion = getenv('TRAVIS_PHP_VERSION');
        $this->excludedEnvironments = array('hhvm', '7.0');
    }

    public function testIncorrectHashDecrypt()
    {
        $result = $this->encryption->decrypt(self::INCORRECT_HASH, self::SALT, self::USERNAME);
        $this->assertNotSame(self::PASSWORD, $result);
    }

    public function testWeakCipherEncryption()
    {
        $encrypted = $this->encryption->encrypt(self::PASSWORD, self::SALT, self::USERNAME);
        $decrypted = $this->encryption->decrypt($encrypted, self::SALT, self::USERNAME);
        $this->assertSame(self::PASSWORD, $decrypted);
        $this->assertFalse($this->weakCipher->isCryptoStrong());
    }

    public function testWeakCipherEncryptionWithoutUsername()
    {
        $encrypted = $this->encryption->encrypt(self::PASSWORD, self::SALT);
        $decrypted = $this->encryption->decrypt($encrypted, self::SALT);
        $this->assertSame(self::PASSWORD, $decrypted);
        $this->assertFalse($this->weakCipher->isCryptoStrong());
    }

    public function testWeakCipherEncryptionPassingSecretKeyAsConstructorParameter()
    {
        $encrypted = $this->ownSecretKeyEncryption->encrypt(self::PASSWORD);
        $decrypted = $this->ownSecretKeyEncryption->decrypt($encrypted);
        $this->assertSame(self::PASSWORD, $decrypted);
        $this->assertFalse($this->weakCipher->isCryptoStrong());
    }

    public function testWeakCipherEncryptWithoutParameters()
    {
        $encrypted = $this->encryption->encrypt();
        $decrypted = $this->encryption->decrypt();
        $this->assertSame(self::HASH_WITHOUT_PARAMETERS, $encrypted);
        $this->assertFalse($decrypted);
    }

    public function testEncryptionWithDefaultCipher()
    {
        $encryption = new Encryption();
        $encrypted = $encryption->encrypt(self::PASSWORD, self::SALT, self::USERNAME);
        $decrypted = $encryption->decrypt($encrypted, self::SALT, self::USERNAME);
        $this->assertSame(self::PASSWORD, $decrypted);
        $this->assertTrue($encryption->getCipher()->isCryptoStrong());
    }

    public function testGetCipherInstance()
    {
        $this->assertInstanceOf('OpenEncryption\Cipher', $this->encryption->getCipher());
    }

    public function testAllCipherMethods()
    {
        if (!in_array($this->environmentVersion, $this->excludedEnvironments)) {
            $supportedCiphers = $this->weakCipher->getSupportedCiphers();
            foreach ($supportedCiphers as $supportedCipher) {
                $cipher = new Cipher($supportedCipher);
                $this->encryption->setCipher($cipher);
                $encrypted = $this->encryption->encrypt(self::PASSWORD, self::SALT, self::USERNAME);
                $decrypted = $this->encryption->decrypt($encrypted, self::SALT, self::USERNAME);
                $this->assertSame(self::PASSWORD, $decrypted);
            }
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetCipherMethodException()
    {
        $this->encryption->setCipher(self::INCORRECT_CIPHER_METHOD);
    }

    /**
     * @expectedException OpenEncryption\Exception\NotSupportedCipherException
     */
    public function testSetNotSupportedCipher()
    {
        $notSupportedCipher = new Cipher(self::NOT_SUPPORTED_CIPHER_METHOD);
        $this->encryption->setCipher($notSupportedCipher);
    }
}
