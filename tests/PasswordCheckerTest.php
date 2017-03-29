<?php
namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\Exceptions\AppException;
use DevpeakIT\PWDSafe\PasswordChecker;
use PHPUnit_Framework_TestCase;

/**
 * Class PasswordChecker
 * @brief Test all (or as many as possible) of the functions in the tested class
 * @package DevpeakIT\PWDSafe\Tests
 */
class PasswordCheckerTest extends PHPUnit_Framework_TestCase
{
        /** @var  PasswordChecker */
        private $passwordchecker;

        public function setUp()
        {
                $this->passwordchecker = new PasswordChecker();
        }
        public function testPasswordStrength()
        {
                $this->expectException(AppException::class);
                $this->passwordchecker->checkPwdStrength("OldPasswd", "OldPasswd", "asdf", "asdf");
        }

        public function testPasswordStrengthWorking()
        {
                $res = $this->passwordchecker->checkPwdStrength("OldPasswd", "OldPasswd", "NewPasswd", "NewPasswd");
                $this->assertTrue($res);
        }

        public function testPasswordStrengthDiffOld()
        {
                $this->expectException(AppException::class);
                $this->passwordchecker->checkPwdStrength("OldPasswd", "Old1Passwd", "NewPasswd", "NewPasswd");
        }

        public function testPasswordStrengthDiffNew()
        {
                $this->expectException(AppException::class);
                $this->passwordchecker->checkPwdStrength("OldPasswd", "OldPasswd", "NewPasswd", "asdf");
        }
}
