<?php

require_once 'PHPUnit/Extensions/DBUnit/MultipleDatabase/DatabaseConfig.php';
require_once 'PHPUnit/Extensions/DBUnit/MultipleDatabase/DatabaseConfig/Builder.php';

/**
 * @covers PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder
 */
class Tests_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_BuilderTest
    extends PHPUnit_Framework_TestCase {

    private $builder;
    private $dataSet;

    protected function setUp() {
        $this->builder =
            new PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder();
        $this->dataSet = 
            $this->getMock('PHPUnit_Extensions_Database_DataSet_IDataSet');
        $this->dataSet
            ->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new ArrayIterator(array(1, 2))));

    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Must provide a connection.
     */
    public function testBuild_whenConnectionNotSet() {
        $this->builder->build();
    }

    public function testConnection() {
        $connection = $this->getMock(
            'PHPUnit_Extensions_Database_DB_IDatabaseConnection');

        $this->builder->connection($connection);

        return $connection;
    }


    /**
     * @depends testConnection
     * @expectedException Exception
     * @expectedExceptionMessage Must provide a data set.
     */
    public function testBuild_whenDataSetNotSet($connection) {
        $this->builder
            ->connection($connection)
            ->build();
    }

    public function testDataSet() {
        $this->builder->dataSet($this->dataSet);
    }

    /**
     * @depends testConnection
     */
    public function testBuild_withDefaultOperations($connection) {
        $this->assertNotNull(
            $this->builder
                ->connection($connection)
                ->dataSet($this->dataSet)
                ->build());
    }

    /**
     * @depends testConnection
     */
    public function testBuild_withDefaultOperationsHasProperConnection(
        $connection) {

        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->build();
        $this->assertEquals($connection, $dbConfig->getConnection());
    }


    /**
     * @depends testConnection
     */
    public function testBuild_withDefaultOperationsHasProperDataSet($connection) {
        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->build();
        $this->assertEquals($this->dataSet, $dbConfig->getDataSet());
    } 

    /**
     * @depends testConnection
     */
    public function testBuild_withDefaultOperationsHasDefaultSetUpOperation(
        $connection) {

        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->build();
        $this->assertEquals(
            PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT(), 
            $dbConfig->getSetUpOperation());
    }

    /**
     * @depends testConnection
     */
    public function testBuild_withDefaultOperationsHasDefaultTearDownOperation(
        $connection) {

        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->build();
        $this->assertEquals(
            PHPUnit_Extensions_Database_Operation_Factory::TRUNCATE(), 
            $dbConfig->getTearDownOperation());
    }

    /**
     * @depends testConnection
     */
    public function testSetUpOperation($connection) {
        $setUpOperation = $this->getMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');
        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->setUpOperation($setUpOperation)
            ->build();
        $this->assertEquals($setUpOperation, $dbConfig->getSetUpOperation());
    }

    /**
     * @depends testConnection
     */
    public function testTearDownOperation($connection) {
        $tearDownOperation = $this->getMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');
        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->tearDownOperation($tearDownOperation)
            ->build();
        $this->assertEquals($tearDownOperation, $dbConfig->getTearDownOperation());
    }

}

