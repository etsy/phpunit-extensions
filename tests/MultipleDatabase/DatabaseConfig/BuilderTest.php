<?php
namespace PHPUnit\Extensions\MultipleDatabase\DatabaseConfig;

use PHPUnit\Framework\TestCase;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\Operation\Factory;
use PHPUnit\DbUnit\Operation\Operation;
use PHPUnit\DbUnit\DataSet\IDataSet;

use ArrayIterator;
use Exception;

/**
 * @covers Builder
 */
class BuilderTest extends TestCase {

    private $builder;
    private $dataSet;

    protected function setUp() {
        $this->builder =
            new Builder();
        $this->dataSet = 

        $this->createMock(IDataSet::class);
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
        $connection = $this->createMock(Connection::class);

        $this->builder->connection($connection);
        
        $this->assertTrue(true);

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
        
        $this->assertTrue(true);
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
            Factory::CLEAN_INSERT(), 
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
            Factory::TRUNCATE(), 
            $dbConfig->getTearDownOperation());
    }

    /**
     * @depends testConnection
     */
    public function testSetUpOperation($connection) {
        $setUpOperation = $this->createMock(Operation::class);
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
        $tearDownOperation = $this->createMock(Operation::class);
        $dbConfig = $this->builder
            ->connection($connection)
            ->dataSet($this->dataSet)
            ->tearDownOperation($tearDownOperation)
            ->build();
        $this->assertEquals($tearDownOperation, $dbConfig->getTearDownOperation());
    }

}

