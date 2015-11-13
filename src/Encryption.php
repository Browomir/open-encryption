<?php

namespace OpenEncryption;

use OpenEncryption\Exception\NotSupportedCipherException;

class Encryption
{
    const SECRET_KEY_LENGTH = 16;

    private $secretKey;
    private $iv;
    private $cipher;
    private $cipherMethod;

    public function __construct($secretKey = null)
    {
        $this->secretKey = $secretKey;
        $this->cipher = new Cipher();
        $this->cipherMethod = $this->cipher->getMethod();
    }

    /**
     * encrypt given string with chosen cipher method e.g. AES-256-CB
     * @param null $input
     * @param null $salt
     * @param null $username
     * @return string
     */
    public function encrypt($input = null, $salt = null, $username = null)
    {
        $this->iv = $this->cipher->getInitializationVector();
        $secretKey = !is_null($this->secretKey) ? $this->secretKey : $this->getKey($salt, $username);
        $encrypted = openssl_encrypt($input, $this->cipherMethod, $secretKey, OPENSSL_RAW_DATA, $this->iv);
        $encrypted = base64_encode($this->iv . $encrypted);

        return $encrypted;
    }

    /**
     * decrypt given hash string with chosen cipher method e.g. AES-256-CB
     * @param null $hash
     * @param null $salt
     * @param null $username
     * @return string
     */
    public function decrypt($hash = null, $salt = null, $username = null)
    {
        $base64DecodedHash = base64_decode($hash);
        $ivLength = $this->cipher->getInitializationVectorLength();
        $this->iv = substr($base64DecodedHash, 0, $ivLength);
        $secretKey = !is_null($this->secretKey) ? $this->secretKey : $this->getKey($salt, $username);
        $decrypted = openssl_decrypt(
            substr($base64DecodedHash, $ivLength), $this->cipherMethod, $secretKey, OPENSSL_RAW_DATA, $this->iv
        );

        return $decrypted;
    }

    /**
     * set given cipher if is supported
     * @param \OpenEncryption\Cipher $cipher
     * @throws NotSupportedCipherException
     */
    public function setCipher($cipher)
    {
        $this->checkCipherInstance($cipher);

        if ($cipher->isSupported()) {
            $this->cipher = $cipher;
            $this->cipherMethod = $cipher->getMethod();
        } else {
            throw new NotSupportedCipherException('Cipher method: "' . $cipher->getMethod() . '" is not supported!!');
        }
    }

    /**
     * returns current cipher
     * @return \OpenEncryption\Cipher
     */
    public function getCipher()
    {
        return $this->cipher;
    }

    /**
     * @param $cipher
     * @throws \InvalidArgumentException when cipher is not instance of OpenEncryption\Cipher
     */
    private function checkCipherInstance($cipher)
    {
        if (!$cipher instanceof Cipher) {
            throw new \InvalidArgumentException('Argument passed must be an instance of Cipher!');
        }
    }

    /**
     * !!! IMPORTANT !!!
     * This is an example of method which should generate a secret key!
     * For security reason, you MUST implements your own getKey method or pass secret key as constructor parameter!
     * @param $salt
     * @param $username
     * @return string
     */
    private function getKey($salt, $username)
    {
        $username = sha1($username);
        $secretString = $salt . $username;
        $secretKey = hash('sha256', $secretString);
        $secretKey = substr($secretKey, 0, self::SECRET_KEY_LENGTH);

        return $secretKey;
    }
}
