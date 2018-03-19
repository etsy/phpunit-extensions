<?php
namespace PHPUnit\Extensions\MultipleDatabase;

use PHPUnit\Framework\TestCase;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\Operation\Operation;

/**
 * @covers DatabaseConfig
 */
class DatabaseConfigTest extends TestCase {

    public function testGetConnection() {
        $connection = $this->getMockBuilder(Connection::class)->getMock();
        $dbConfig = new DatabaseConfig(
             $connection,
             NULL,
             NULL,
             NULL);
        $this->assertEquals($connection, $dbConfig->getConnection());
    }

    public function testGetDataSet() {
        $dataSet = $this->getMockBuilder(IDataSet::class)->getMock();
        $dbConfig = new DatabaseConfig(
             NULL,
             $dataSet,
             NULL,
             NULL);
        $this->assertEquals($dataSet, $dbConfig->getDataSet());
    }

    public function testGetSetUpOperation() {
        $setUpOperation = $this->getMockBuilder(Operation::class)->getMock();
        $dbConfig = new DatabaseConfig(
             NULL,
             NULL,
             $setUpOperation,
             NULL);
        $this->assertEquals($setUpOperation, $dbConfig->getSetUpOperation());
    }

    public function testGetTearDownOperation() {
        $tearDownOperation = $this->getMockBuilder(Operation::class)->getMock();
        $dbConfig = new DatabaseConfig(
             NULL,
             NULL,
             NULL,
             $tearDownOperation);
        $this->assertEquals($tearDownOperation, $dbConfig->getTearDownOperation());
    }
}

