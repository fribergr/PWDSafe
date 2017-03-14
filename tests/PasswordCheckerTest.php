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
    public function testPasswordStrength()
    {
        $this->expectException(AppException::class);
        PasswordChecker::checkPwdStrength("OldPasswd", "OldPasswd", "asdf", "asdf");
    }

    public function testPasswordStrengthWorking()
    {
        $res = PasswordChecker::checkPwdStrength("OldPasswd", "OldPasswd", "NewPasswd", "NewPasswd");
        $this->assertTrue($res);
    }

    public function testPasswordStrengthDiffOld()
    {
        $this->expectException(AppException::class);
        PasswordChecker::checkPwdStrength("OldPasswd", "Old1Passwd", "NewPasswd", "NewPasswd");
    }

    public function testPasswordStrengthDiffNew()
    {
        $this->expectException(AppException::class);
        PasswordChecker::checkPwdStrength("OldPasswd", "OldPasswd", "NewPasswd", "asdf");
    }
}
