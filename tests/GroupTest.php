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

    public function testGivePermission()
    {
            $container = new Container();

            $PDOmock = $this->getMockBuilder('\PDO')->disableOriginalConstructor()->getMock();
            $PDOstmt = $this->getMockBuilder('PDOStatement')->getMock();
            $PDOstmt->expects($this->once())->method('execute')->will($this->returnValue(1));
            $PDOmock->expects($this->once())->method('prepare')->will($this->returnValue($PDOstmt));

            /** @var \PDO $PDOmock */
            $container->setDB($PDOmock);

            $group = new Group($container);
            $group->setAll(['id' => 1, 'name' => "Something"]);
            $this->assertEquals("Something", $group->name);

            $group->givePermission(5);
            $this->expectOutputString("");
    }

    public function testGetName()
    {
            $container = new Container();

            $result = 'Something Strange';

            $PDOmock = $this->getMockBuilder('\PDO')->disableOriginalConstructor()->getMock();
            $PDOstmt = $this->getMockBuilder('PDOStatement')->getMock();
            $PDOstmt->expects($this->once())->method('execute')->will($this->returnValue(1));
            $PDOstmt->expects($this->once())->method('fetchColumn')->will($this->returnValue($result));
            $PDOmock->expects($this->once())->method('prepare')->will($this->returnValue($PDOstmt));

            /** @var \PDO $PDOmock */
            $container->setDB($PDOmock);

            $group = new Group($container);
            $group->id = 5;
            $this->assertEquals("Something Strange", $group->getName());
    }

    public function testGetNameNotNull()
    {
            $container = new Container();
            $group = new Group($container);
            $group->name = "A not null name";
            $this->assertEquals("A not null name", $group->getName());
    }
}