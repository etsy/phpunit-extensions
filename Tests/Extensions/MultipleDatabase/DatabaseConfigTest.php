<?php

namespace PHPUnit\Extensions\MultipleDatabase;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\Operation\Operation;

/**
 * @covers DatabaseConfig
 */
class Tests_Extensions_MultipleDatabase_DatabaseConfigTest extends TestCase {

    public function testGetConnection() {
        $connection = $this->createMock(Connection::class);
        $dbConfig = new DatabaseConfig(
             $connection,
             NULL,
             NULL,
             NULL);
        $this->assertEquals($connection, $dbConfig->getConnection());
    }

    public function testGetDataSet() {
        $dataSet = $this->createMock(IDataSet::class);
        $dbConfig = new DatabaseConfig(
             NULL,
             $dataSet,
             NULL,
             NULL);
        $this->assertEquals($dataSet, $dbConfig->getDataSet());
    }

    public function testGetSetUpOperation() {
        $setUpOperation = $this->createMock(Operation::class);
        $dbConfig = new DatabaseConfig(
             NULL,
             NULL,
             $setUpOperation,
             NULL);
        $this->assertEquals($setUpOperation, $dbConfig->getSetUpOperation());
    }

    public function testGetTearDownOperation() {
        $tearDownOperation = $this->createMock(Operation::class);
        $dbConfig = new DatabaseConfig(
             NULL,
             NULL,
             NULL,
             $tearDownOperation);
        $this->assertEquals($tearDownOperation, $dbConfig->getTearDownOperation());
    }
}

