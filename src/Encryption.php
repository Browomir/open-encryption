<?php

namespace OpenEncryption;

class Encryption
{
    const ENCRYPT_METHOD = 'AES-128-ECB';
    const SECRET_KEY_LENGTH = 16;

    private $secretKey;

    public function __construct($secretKey = null)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param $input
     * @param $salt
     * @param string $username
     * @return string
     */
    public function encrypt($input, $salt, $username = '')
    {
        $secretKey = !is_null($this->secretKey) ? $this->secretKey : $this->getKey($salt, $username);
        $encrypted = openssl_encrypt($input, self::ENCRYPT_METHOD, $secretKey, OPENSSL_RAW_DATA);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
     * decrypt given hash string with ENCRYPT_METHOD e.g. AES-128-ECB
     * @param $hash
     * @param $salt
     * @param string $username
     * @return string
     */
    public function decrypt($hash, $salt, $username = '')
    {
        $secretKey = !is_null($this->secretKey) ? $this->secretKey : $this->getKey($salt, $username);
        $decrypted = openssl_decrypt(base64_decode($hash), self::ENCRYPT_METHOD, $secretKey, OPENSSL_RAW_DATA);

        return $decrypted;
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
