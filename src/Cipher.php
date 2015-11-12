<?php

namespace OpenEncryption;

use OpenEncryption\Exception\InvalidCipherException;

class Cipher
{
    const DEFAULT_CIPHER_METHOD = 'AES-256-CBC';

    private $iv;
    private $ivLength;
    private $method;
    private $isCryptoStrong;
    private $supported;

    public function __construct($method = null)
    {
        $this->setMethod($method);
    }

    /**
     * @param null $method
     * @throws InvalidCipherException
     */
    private function setMethod($method = null)
    {
        $this->method = self::DEFAULT_CIPHER_METHOD;

        if (!is_null($method) && is_string($method)) {
            $availableMethods = openssl_get_cipher_methods();
            if (in_array($method, $availableMethods)) {
                $this->method = $method;
            } else {
                throw new InvalidCipherException('Cipher method: "' . $method . '" does not exist!');
            }
        }
    }

    /**
     * this make simple test of current cipher method
     * @param null $method
     * @return bool
     */
    private function checkIfMethodIsSupported($method = null)
    {
        $cipherMethod = !is_null($method) ? $method : $this->method;
        $input = 'testCipher';
        $key = 'KeyForTestCipher';
        $encrypted = openssl_encrypt($input, $cipherMethod, $key, OPENSSL_RAW_DATA, $this->iv);
        $decrypted = openssl_decrypt($encrypted, $cipherMethod, $key, OPENSSL_RAW_DATA, $this->iv);
        $result = $input === $decrypted ? true : false;

        if (is_null($method)) {
            $this->supported = $result;
        }

        return $result;
    }

    /**
     * generate initialization vector for strong cipher methods
     * @param null $length
     * @return string
     */
    public function getInitializationVector($length = null)
    {
        if (!is_null($length)) {
            if (!is_integer($length)) {
                throw new \InvalidArgumentException('Length is not an integer!');
            }
            $ivLength = $length;
        } else {
            $ivLength = $this->getInitializationVectorLength();
        }

        $isCryptoStrong = false;
        $this->iv = openssl_random_pseudo_bytes($ivLength, $isCryptoStrong);

        if (is_null($length)) {
            $this->isCryptoStrong = $isCryptoStrong;
        }

        return $this->iv;
    }

    /**
     * returns initialization vector length for current cipher method
     * @return int
     */
    public function getInitializationVectorLength()
    {
        if (!isset($this->ivLength)) {
            $this->ivLength = openssl_cipher_iv_length($this->method);
        }

        return $this->ivLength;
    }

    /**
     * check if is cryptographically strong
     * @return bool
     */
    public function isCryptoStrong()
    {
        if (!isset($this->isCryptoStrong)) {
            $this->getInitializationVector();
        }

        return $this->isCryptoStrong;
    }

    /**
     * check if cipher method is supported
     * @return bool
     */
    public function isSupported()
    {
        if (!isset($this->supported)) {
            $this->getInitializationVector();
            $this->checkIfMethodIsSupported();
        }

        return $this->supported;
    }

    /**
     * returns cipher name
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * returns array of all supported ciphers
     * @return array
     */
    public function getSupportedCiphers()
    {
        $availableMethods = openssl_get_cipher_methods();
        $supportedCiphers = array();

        foreach ($availableMethods as $method) {
            $length = openssl_cipher_iv_length($method);
            $this->getInitializationVector($length);
            if ($this->checkIfMethodIsSupported($method)) {
                $supportedCiphers[] = $method;
            }
        }

        return $supportedCiphers;
    }
}
