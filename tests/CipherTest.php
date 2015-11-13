<?php

namespace OpenEncryptionTest;

use OpenEncryption\Cipher;

class CipherTest extends \PHPUnit_Framework_TestCase
{
    const WEAK_CIPHER_METHOD = 'CAMELLIA-128-ECB';
    const INCORRECT_CIPHER_METHOD = 'some-incorrect-cipher';
    const NOT_SUPPORTED_CIPHER_METHOD = 'aes-256-xts';
    const DEFAULT_CIPHER_IV_LENGTH = 16;

    private $defaultCipher;
    private $weakCipher;
    private $notSupportedCipher;

    public function setUp()
    {
        $this->defaultCipher = new Cipher();
        $this->weakCipher = new Cipher(self::WEAK_CIPHER_METHOD);
        $this->notSupportedCipher = new Cipher(self::NOT_SUPPORTED_CIPHER_METHOD);
    }

    public function testCipherMethodName()
    {
        $this->assertSame(Cipher::DEFAULT_CIPHER_METHOD, $this->defaultCipher->getMethod());
        $this->assertSame(self::WEAK_CIPHER_METHOD, $this->weakCipher->getMethod());
        $this->assertSame(self::NOT_SUPPORTED_CIPHER_METHOD, $this->notSupportedCipher->getMethod());
    }

    public function testIfCipherIsSupported()
    {
        $this->assertTrue($this->defaultCipher->isSupported());
        $this->assertTrue($this->weakCipher->isSupported());
        $this->assertFalse($this->notSupportedCipher->isSupported());
    }

    public function testIfCryptoIsStrong()
    {
        $this->assertTrue($this->defaultCipher->isCryptoStrong());
        $this->assertFalse($this->weakCipher->isCryptoStrong());
        $this->assertTrue($this->notSupportedCipher->isCryptoStrong());
    }

    public function testInitializationVectorLength()
    {
        $this->assertEquals(self::DEFAULT_CIPHER_IV_LENGTH, $this->defaultCipher->getInitializationVectorLength());
        $this->assertEquals(0, $this->weakCipher->getInitializationVectorLength());
        $this->assertEquals(self::DEFAULT_CIPHER_IV_LENGTH, $this->notSupportedCipher->getInitializationVectorLength());
    }

    public function testGetInitializationVectorForDefaultCipher()
    {
        $this->assertInternalType('string', $this->defaultCipher->getInitializationVector());
        $this->assertEquals(self::DEFAULT_CIPHER_IV_LENGTH, strlen($this->defaultCipher->getInitializationVector()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInitializationVectorWithIncorrectArgument()
    {
        $this->defaultCipher->getInitializationVector('someIncorrectArgument');
        $this->weakCipher->getInitializationVector(new \stdClass());
        $this->notSupportedCipher->getInitializationVector(123.456);
    }

    /**
     * @expectedException OpenEncryption\Exception\InvalidCipherException
     */
    public function testPassNotExistingCipherMethodToConstructor()
    {
        new Cipher('some-notExisting-cipher');
        new Cipher(123);
        new Cipher(4.321);
    }

    public function testSupportedCiphersList()
    {
        $supported = $this->defaultCipher->getSupportedCiphers();
        $this->assertInternalType('array', $supported);
        $cipherMethods = openssl_get_cipher_methods();
        foreach ($supported as $method) {
            $this->assertTrue(in_array($method, $cipherMethods));
        }
    }
}
