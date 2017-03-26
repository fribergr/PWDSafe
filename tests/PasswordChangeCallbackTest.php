<?php

namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\Callbacks\Api\PasswordChangeCallback;
use DevpeakIT\PWDSafe\Container;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\User;
use PHPUnit\Framework\TestCase;


class PasswordChangeCallbackTest extends TestCase
{
        private $passwordchangecallback;
        public function setUp()
        {
                $container = new Container();
                $userstub = $this->createMock(User::class);
                $userstub->expects($this->once())->method('changePassword')->willReturn(true);
                $container->setUser($userstub);
                $fcstub = $this->createMock(FormChecker::class);
                $fcstub->expects($this->once())->method('checkRequiredFields')->willReturn(true);
                $container->setFormchecker($fcstub);
                $this->passwordchangecallback = new PasswordChangeCallback($container);
        }

        public function testPasswordChangeCallback()
        {
                $_POST['username'] = "user";
                $_POST['old_password'] = "oldpass";
                $_POST['new_password'] = "newpass";
                $this->passwordchangecallback->post();
                $this->expectOutputString('');
        }
}