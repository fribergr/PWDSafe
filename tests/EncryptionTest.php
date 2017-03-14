<?php
namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\Encryption;
use PHPUnit_Framework_TestCase;

/**
 * Class EncryptionTest
 * @brief Test all (or as many as possible) of the functions in the tested class
 * @package DevpeakIT\PWDSafe\Tests
 */
class EncryptionTest extends PHPUnit_Framework_TestCase
{
        /**
         * @var Encryption
         */
        private $encryption;

        private $privkey;
        private $pubkey;

        public function setUp()
        {
                $this->encryption = new Encryption();
                list($this->privkey, $this->pubkey) = Encryption::genNewKeys();
        }

        public function testEncDec()
        {
                $encoded = $this->encryption->enc("My secret credential", "SomePassword");
                $this->assertNotEquals("My secret credential", $encoded);
                $decrypted = $this->encryption->dec($encoded, "SomePassword");
                $this->assertEquals("My secret credential", $decrypted);
        }

        public function testEncWithPub()
        {
            $data = "Testdata";
            $enc = $this->encryption->encWithPub($data, $this->pubkey);
            $this->assertNotEquals($data, $enc);
            $dec = $this->encryption->decWithPriv($enc, $this->privkey);
            $this->assertEquals($data, $dec);
        }
}
