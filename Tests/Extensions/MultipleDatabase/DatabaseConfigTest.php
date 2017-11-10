<?php

require_once 'PHPUnit/Extensions/MultipleDatabase/DatabaseConfig.php';

/**
 * @covers PHPUnit_Extensions_MultipleDatabase_DatabaseConfig
 */
class Tests_Extensions_MultipleDatabase_DatabaseConfigTest
    extends PHPUnit_Framework_TestCase {

    public function testGetConnection() {
        $connection = $this->createMock(
            'PHPUnit_Extensions_Database_DB_IDatabaseConnection');
        $dbConfig = new PHPUnit_Extensions_MultipleDatabase_DatabaseConfig(
             $connection,
             NULL,
             NULL,
             NULL);
        $this->assertEquals($connection, $dbConfig->getConnection());
    }

    public function testGetDataSet() {
        $dataSet = $this->createMock('PHPUnit_Extensions_Database_DataSet_IDataSet');
        $dbConfig = new PHPUnit_Extensions_MultipleDatabase_DatabaseConfig(
             NULL,
             $dataSet,
             NULL,
             NULL);
        $this->assertEquals($dataSet, $dbConfig->getDataSet());
    }

    public function testGetSetUpOperation() {
        $setUpOperation = $this->createMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');
        $dbConfig = new PHPUnit_Extensions_MultipleDatabase_DatabaseConfig(
             NULL,
             NULL,
             $setUpOperation,
             NULL);
        $this->assertEquals($setUpOperation, $dbConfig->getSetUpOperation());
    }

    public function testGetTearDownOperation() {
        $tearDownOperation = $this->createMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');
        $dbConfig = new PHPUnit_Extensions_MultipleDatabase_DatabaseConfig(
             NULL,
             NULL,
             NULL,
             $tearDownOperation);
        $this->assertEquals($tearDownOperation, $dbConfig->getTearDownOperation());
    }
}

