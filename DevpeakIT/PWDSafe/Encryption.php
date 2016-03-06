<?php
namespace DevpeakIT\PWDSafe;

/**
 * Class Encryption Used for encrypting and decrypting data via OpenSSL
 * @package DevpeakIT\PWDSafe
 */
class Encryption
{
        /**
         * @param $data string to encrypt
         * @param $pwd string to use as key for the encryption
         * @return string
         */
        public function enc($data, $pwd)
        {
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes256"));
                $encrypted = openssl_encrypt($data, "aes256", $pwd, 0, $iv);
                return $encrypted . ":" . bin2hex($iv);
        }

        /**
         * @param $data string to decrypt
         * @param $pwd string to use as key for the decryption
         * @return string
         */
        public function dec($data, $pwd)
        {
                list($data, $biniv) = explode(":", $data);
                $iv = hex2bin($biniv);
                return openssl_decrypt($data, "aes256", $pwd, 0, $iv);
        }
}
