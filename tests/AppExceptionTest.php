<?php
namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\Exceptions\AppException;
use PHPUnit_Framework_TestCase;

/**
 * Class EncryptionTest
 * @brief Test all (or as many as possible) of the functions in the tested class
 * @package DevpeakIT\PWDSafe\Tests
 */
class AppExceptionTest extends PHPUnit_Framework_TestCase
{
        public function testNewException()
        {
                $exception = new AppException("Some test message");
                $this->assertEquals("Some test message", $exception->getMessage());
                $this->assertEquals(["Some test message"], $exception->getErrors());
        }
}
