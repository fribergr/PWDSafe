<?php
namespace DevpeakIT\PWDSafe\Tests;

use DevpeakIT\PWDSafe\Container;
use DevpeakIT\PWDSafe\Group;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function testCreate()
    {
        $container = new Container();

        $PDOmock = $this->getMockBuilder('\PDO')->disableOriginalConstructor()->getMock();
        $PDOmock->expects($this->any())->method('lastInsertId')->will($this->returnValue('1'));
        $PDOstmt = $this->getMockBuilder('PDOStatement')->getMock();
        $PDOstmt->expects($this->any())->method('execute')->will($this->returnValue(1));
        $PDOmock->expects($this->any())->method('prepare')->will($this->returnValue($PDOstmt));

        /** @var \PDO $PDOmock */
        $container->setDB($PDOmock);

        $group = new Group($container);
        $grp = $group->create("Hello");
        $this->assertEquals("Hello", $grp->name);
    }
}