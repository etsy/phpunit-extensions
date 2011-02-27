<?php

require_once 'PHPUnit/Extensions/DBUnit/MultipleDatabase/DatabaseConfig.php';

/**
 * @covers PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig
 */
class Tests_Extensions_DBUnit_MultipleDatabase_DatabaseConfigTest
    extends PHPUnit_Framework_TestCase {

    public function testGetConnection() {
        $connection = $this->getMock(
            'PHPUnit_Extensions_Database_DB_IDatabaseConnection');
        $dbConfig = new PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig(
             $connection,
             NULL,
             NULL,
             NULL);
        $this->assertEquals($connection, $dbConfig->getConnection());
    }

    public function testGetDataSet() {
        $dataSet = $this->getMock('PHPUnit_Extensions_Database_DataSet_IDataSet');
        $dbConfig = new PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig(
             NULL,
             $dataSet,
             NULL,
             NULL);
        $this->assertEquals($dataSet, $dbConfig->getDataSet());
    }

    public function testGetSetUpOperation() {
        $setUpOperation = $this->getMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');
        $dbConfig = new PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig(
             NULL,
             NULL,
             $setUpOperation,
             NULL);
        $this->assertEquals($setUpOperation, $dbConfig->getSetUpOperation());
    }

    public function testGetTearDownOperation() {
        $tearDownOperation = $this->getMock(
            'PHPUnit_Extensions_Database_OPeration_IDatabaseOperation');
        $dbConfig = new PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig(
             NULL,
             NULL,
             NULL,
             $tearDownOperation);
        $this->assertEquals($tearDownOperation, $dbConfig->getTearDownOperation());
    }
}

