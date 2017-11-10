<?php

require_once 'PHPUnit/Extensions/MultipleDatabase/DatabaseConfig.php';
require_once 'PHPUnit/Extensions/MultipleDatabase/Tester.php';

/**
 * @covers PHPUnit_Extensions_MultipleDatabase_Tester
 */
class Tests_Extensions_MultipleDatabase_TesterTest 
extends PHPUnit_Framework_TestCase {

    private $dbConfig;

    protected function setUp() {
        parent::setUp();

        $connection = $this->createMock(
            'PHPUnit_Extensions_Database_DB_IDatabaseConnection');
        $dataSet = $this->createMock(
            'PHPUnit_Extensions_Database_DataSet_IDataSet');
        $setUpOperation = $this->createMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');
        $tearDownOperation = $this->createMock(
            'PHPUnit_Extensions_Database_Operation_IDatabaseOperation');

        $this->dbConfig = $this->getMockBuilder(
            'PHPUnit_Extensions_MultipleDatabase_DatabaseConfig')
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
            new PHPUnit_Extensions_MultipleDatabase_Tester($this->dbConfig);
        $this->assertEquals(
            $this->dbConfig->getConnection(),
            $tester->getConnection());
    }
}
