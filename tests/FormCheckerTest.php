<?php
namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\FormChecker;
use PHPUnit_Framework_TestCase;

class FormCheckerTest extends PHPUnit_Framework_TestCase
{
        public function testRequiredFields()
        {
                $_POST['username'] = "Something";
                FormChecker::checkRequiredFields(['username']);
                $this->expectOutputString("");
        }

        public function testRequiredFieldsWhichShouldFail()
        {
                $_POST['username'] = "Something";
                $_POST['password'] = "";
                FormChecker::checkRequiredFields(["username", "password", "key"]);
                $this->expectOutputRegex("/Fail/");
        }

        public function testRequiredFieldsWhichAlsoShouldFail()
        {
                $_POST['username'] = "Something";
                $_POST['password'] = "";
                FormChecker::checkRequiredFields(["username", "password"]);
                $this->expectOutputRegex("/Fail/");
        }

        public function testCheckFieldLengthFail()
        {
                $_POST['username'] = "Something";
                FormChecker::checkFieldLength("username", 10);
                $this->expectOutputRegex("/Fail/");
        }

        public function testCheckFieldLengthOk()
        {
                $_POST['username'] = "Something";
                FormChecker::checkFieldLength("username", 6);
                $this->expectOutputString("");
        }
}
