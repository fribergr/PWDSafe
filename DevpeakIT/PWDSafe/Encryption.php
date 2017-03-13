<?php
namespace DevpeakIT\PWDSafe;

/**
 * Class Encryption Used for encrypting and decrypting data via OpenSSL
 * @package DevpeakIT\PWDSafe
 */
class Encryption
{
        public static function genNewKeys()
        {
                $config = array(
                   "digest_alg" => "sha512",
                   "private_key_bits" => 4096,
                   "private_key_type" => OPENSSL_KEYTYPE_RSA,
                );
                $res = openssl_pkey_new($config);
                openssl_pkey_export($res, $privKey);
                $pubKey = openssl_pkey_get_details($res);
                $pubKey = $pubKey["key"];
                return [$privKey, $pubKey];
        }

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

        public function encWithPub($data, $pubkey)
        {
                openssl_public_encrypt($data, $encrypted, $pubkey);
                return $encrypted;
        }

        public function decWithPriv($data, $privkey)
        {
                openssl_private_decrypt($data, $decrypted, $privkey);
                return $decrypted;
        }
}
