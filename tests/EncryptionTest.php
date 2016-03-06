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

        public function setUp()
        {
                $this->encryption = new Encryption();
        }

        public function testEncDec()
        {
                $encoded = $this->encryption->enc("My secret credential", "SomePassword");
                $decrypted = $this->encryption->dec($encoded, "SomePassword");
                $this->assertEquals("My secret credential", $decrypted);
        }
}
