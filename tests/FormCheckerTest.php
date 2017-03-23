<?php
namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\FormChecker;
use PHPUnit_Framework_TestCase;

class FormCheckerTest extends PHPUnit_Framework_TestCase
{
        public function testRequiredFields()
        {
                $_POST['username'] = "Something";
                $res = FormChecker::checkRequiredFields(['username']);
                $this->expectOutputString("");
                $this->assertTrue($res);
        }

        public function testRequiredFieldsWhichShouldFail()
        {
                $_POST['username'] = "Something";
                $_POST['password'] = "";
                $res = FormChecker::checkRequiredFields(["username", "password", "key"]);
                $this->expectOutputRegex("/Fail/");
                $this->assertFalse($res);
        }

        public function testRequiredFieldsWhichAlsoShouldFail()
        {
                $_POST['username'] = "Something";
                $_POST['password'] = "";
                $res = FormChecker::checkRequiredFields(["username", "password"]);
                $this->expectOutputRegex("/Fail/");
                $this->assertFalse($res);
        }

        public function testCheckFieldLengthFail()
        {
                $_POST['username'] = "Something";
                $res = FormChecker::checkFieldLength("username", 10);
                $this->expectOutputRegex("/Fail/");
                $this->assertFalse($res);
        }

        public function testCheckFieldLengthOk()
        {
                $_POST['username'] = "Something";
                $res = FormChecker::checkFieldLength("username", 6);
                $this->expectOutputString("");
                $this->assertTrue($res);
        }
}
