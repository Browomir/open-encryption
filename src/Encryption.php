<?php

namespace OpenEncryption;

class Encryption
{
    const ENCRYPT_METHOD = 'AES-128-ECB';
    const SECRET_KEY_LENGTH = 16;

    /**
     * encrypt given string with ENCRYPT_METHOD e.g. AES-128-ECB
     * @param $input
     * @param $salt
     * @param $username
     * @return string base64 encoded
     */
    public static function encrypt($input, $salt, $username = '')
    {
        $secretKey = self::getKey($salt, $username);
        $encrypted = openssl_encrypt($input, self::ENCRYPT_METHOD, $secretKey, OPENSSL_RAW_DATA);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
     * decrypt given hash string with ENCRYPT_METHOD e.g. AES-128-ECB
     * @param $hash
     * @param $salt
     * @param $username
     * @return string
     */
    public static function decrypt($hash, $salt, $username = '')
    {
        $secretKey = self::getKey($salt, $username);
        $decrypted = openssl_decrypt(base64_decode($hash), self::ENCRYPT_METHOD, $secretKey, OPENSSL_RAW_DATA);

        return $decrypted;
    }

    /**
     * generate key to encryption/decryption
     * @param $salt
     * @param $username
     * @return string
     */
    private static function getKey($salt, $username)
    {
        $secretString = $salt . $username;
        $secretKey = sha1($secretString);
        $secretKey = substr($secretKey, 0, self::SECRET_KEY_LENGTH);

        return $secretKey;
    }
}
