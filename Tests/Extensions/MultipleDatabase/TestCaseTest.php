<?php

require_once 'PHPUnit/Extensions/MultipleDatabase/TestCase.php';

/**
 * @covers PHPUnit_Extensions_MultipleDatabase_TestCase
 */
class Tests_Extensions_MultipleDatabase_TestCaseTest 
extends PHPUnit_Framework_TestCase {

    private $dataSet;

    protected function setUp() {
        parent::setUp();    
        $this->dataSet = 
            $this->getMock('PHPUnit_Extensions_Database_DataSet_IDataSet');
        $this->dataSet
            ->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new ArrayIterator(array(1,2))));
    }

    public function testGetDatabaseTesters() {
        $dbConfigs = array();
        $builder =
            new PHPUnit_Extensions_MultipleDatabase_DatabaseConfig_Builder();
        $dbConfigs[] = $builder
            ->connection(
                 $this->getMock(
                     'PHPUnit_Extensions_Database_DB_IDatabaseConnection'))
            ->dataSet($this->dataSet)
            ->build();

        $builder =
            new PHPUnit_Extensions_MultipleDatabase_DatabaseConfig_Builder();
        $dbConfigs[] = $builder
            ->connection(
                $this->getMock(
                    'PHPUnit_Extensions_Database_DB_IDatabaseConnection'))
            ->dataSet($this->dataSet)
            ->build();
        
        $expected = array(
            new PHPUnit_Extensions_MultipleDatabase_Tester($dbConfigs[0]),
            new PHPUnit_Extensions_MultipleDatabase_Tester($dbConfigs[1]));
	
        $testCase = $this->getMockForAbstractClass(
            'PHPUnit_Extensions_MultipleDatabase_TestCase');
        $testCase
            ->expects($this->any())
            ->method('getDatabaseConfigs')
            ->will($this->returnValue($dbConfigs));

        $this->assertEquals($expected, $testCase->getDatabaseTesters());

        return $testCase;
    }

    public function testSetUpDatabaseTesters() {
        $testers = array();

        $testers[] = $this->getMockBuilder(
            'PHPUnit_Extensions_MultipleDatabase_Tester')
            ->disableOriginalConstructor()
            ->getMock();
        $testers[0]
            ->expects($this->once())
            ->method('onSetUp');

        $testers[] = $this->getMockBuilder(
            'PHPUnit_Extensions_MultipleDatabase_Tester')
            ->disableOriginalConstructor()
            ->getMock();
        $testers[1]
            ->expects($this->once())
            ->method('onSetUp');
 
        $testCase = $this->getMockForAbstractClass(
            'PHPUnit_Extensions_MultipleDatabase_TestCase');
        
        $testCase->setUpDatabaseTesters($testers);
    }

    public function testTearDownDatabaseTesters() {
        $testers = array();

        $testers[] = $this->getMockBuilder(
            'PHPUnit_Extensions_MultipleDatabase_Tester')
            ->disableOriginalConstructor()
            ->getMock();
        $testers[0]
            ->expects($this->once())
            ->method('onTearDown');

        $testers[] = $this->getMockBuilder(
            'PHPUnit_Extensions_MultipleDatabase_Tester')
            ->disableOriginalConstructor()
            ->getMock();
        $testers[1]
            ->expects($this->once())
            ->method('onTearDown');
 
        $testCase = $this->getMockForAbstractClass(
            'PHPUnit_Extensions_MultipleDatabase_TestCase');
        
        $testCase->tearDownDatabaseTesters($testers);
    }

    /**
     * @depends testGetDatabaseTesters
     */
    public function testSetUp($testCase) {
        $testCase->setUp();
        $this->assertObjectHasAttribute('testers', $testCase);
    }

    /**
     * @depends testGetDatabaseTesters
     */
    public function testTearDown($testCase) {
        $testCase->tearDown();
        $this->assertObjectHasAttribute('testers', $testCase);
    }

    public function testAssertTablesEqual() {
        $expected = $this->getMock('PHPUnit_Extensions_Database_DataSet_ITable');
        $actual = $expected;
        PHPUnit_Extensions_MultipleDatabase_TestCase
            ::assertTablesEqual($expected, $actual);
    }  

    public function testAssertDataSetsEqual() {
        $expected = $this->getMock('PHPUnit_Extensions_Database_DataSet_IDataSet');
        $actual = $expected;
        PHPUnit_Extensions_MultipleDatabase_TestCase
            ::assertDataSetsEqual($expected, $actual);
    }  
}
