<?php
namespace PHPUnit\Extensions\MultipleDatabase;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\Operation\Operation;

/**
 * @covers Tester
 */
class TesterTest extends \PHPUnit\Framework\TestCase {

    private $dbConfig;

    protected function setUp() {
        parent::setUp();

        $connection = $this->createMock(Connection::class);
        $dataSet = $this->createMock(IDataSet::class);
        $setUpOperation = $this->createMock(Operation::class);
        $tearDownOperation = $this->createMock(Operation::class);

        $this->dbConfig = $this->getMockBuilder(DatabaseConfig::class)
            ->setConstructorArgs(
                array(
                    $connection, 
                    $dataSet, 
                    $setUpOperation, 
                    $tearDownOperation
                ))
            ->getMock();
        $this->dbConfig
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($connection));
        $this->dbConfig
            ->expects($this->any())
            ->method('getDataSet')
            ->will($this->returnValue($dataSet));
        $this->dbConfig
            ->expects($this->any())
            ->method('getSetUpOperation')
            ->will($this->returnValue($setUpOperation));
        $this->dbConfig
            ->expects($this->any())
            ->method('getTearDownOperation')
            ->will($this->returnValue($tearDownOperation));
    }

    public function testConstruct_setsConnectionCorrectly() {
        $tester = 
            new Tester($this->dbConfig);
        $this->assertEquals(
            $this->dbConfig->getConnection(),
            $tester->getConnection());
    }
}
