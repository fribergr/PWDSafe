<?php
namespace DevpeakIT\PWDSafe\Tests;

use PHPUnit\Framework\TestCase;

class PreLogonRegisterCallbackTest extends TestCase
{
        public function testPost()
        {
                $container = new \DevpeakIT\PWDSafe\Container();
                $container->setFormchecker(new \DevpeakIT\PWDSafe\FormChecker());
                $result = [
                    ['id' => 1]
                ];

                $PDOmock = $this->getMockBuilder('\PDO')->disableOriginalConstructor()->getMock();
                $PDOstmt = $this->getMockBuilder('PDOStatement')->getMock();
                $PDOstmt->expects($this->any())->method('fetch')->will($this->returnValue($result));
                $PDOstmt->expects($this->any())->method('execute')->will($this->returnValue(1));
                $PDOstmt->expects($this->any())->method('rowCount')->will($this->returnValue(1));
                $PDOmock->expects($this->any())->method('prepare')->will($this->returnValue($PDOstmt));

                $container->setDB($PDOmock);

                $_POST['user'] = "Something invalid";
                $_POST['pass'] = "Something";
                ob_start();
                $testsubject = new \DevpeakIT\PWDSafe\Callbacks\Api\PreLogonRegisterCallback($container);
                $testsubject->post();
                $res = ob_get_clean();
                $res = json_decode($res, true);
                $this->assertEquals('Your username contains invalid characters', $res['reason']);

                $_POST['user'] = "Something_valid";
                $_POST['pass'] = "Something";
                ob_start();
                $testsubject = new \DevpeakIT\PWDSafe\Callbacks\Api\PreLogonRegisterCallback($container);
                $testsubject->post();
                $res = ob_get_clean();
                $res = json_decode($res, true);
                $this->assertEquals('Account already exists', $res['reason']);

                $PDOmock = $this->getMockBuilder('\PDO')->disableOriginalConstructor()->getMock();
                $PDOstmt = $this->getMockBuilder('PDOStatement')->getMock();
                $PDOstmt->expects($this->any())->method('fetch')->will($this->returnValue([]));
                $PDOstmt->expects($this->any())->method('execute')->will($this->returnValue(1));
                $PDOstmt->expects($this->any())->method('rowCount')->will($this->returnValue(0));
                $PDOmock->expects($this->any())->method('prepare')->will($this->returnValue($PDOstmt));
                $container->setDB($PDOmock);

                $userstub = $this->getMockBuilder(\DevpeakIT\PWDSafe\User::class)->getMock();
                $userstub->expects($this->any())->method('registerUser')->willReturn(true);
                $container->setUser($userstub);
                $encryptionstub = $this->getMockBuilder(\DevpeakIT\PWDSafe\Encryption::class)->getMock();
                $container->setEncryption($encryptionstub);

                $_POST['user'] = "Something_valid";
                $_POST['pass'] = "Something";
                ob_start();
                $testsubject = new \DevpeakIT\PWDSafe\Callbacks\Api\PreLogonRegisterCallback($container);
                $testsubject->post();
                $res = ob_get_clean();
                $res = json_decode($res, true);
                $this->assertEquals('OK', $res['status']);
        }
}