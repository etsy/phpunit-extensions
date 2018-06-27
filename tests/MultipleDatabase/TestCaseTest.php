<?php
namespace PHPUnit\Extensions\MultipleDatabase;

use ArrayIterator;
use PHPUnit\Extensions\MultipleDatabase\DatabaseConfig\Builder;

use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\DefaultTable;
use PHPUnit\DbUnit\DataSet\DefaultTableMetaData;
use PHPUnit\DbUnit\DataSet\CompositeDataSet;

/**
 * @covers TestCase
 */
class TestCaseTest extends \PHPUnit\Framework\TestCase {

    private $dataSet;

    protected function setUp() {
        parent::setUp();    
        $this->dataSet = 
        $this->createMock(IDataSet::class);
        $this->dataSet
            ->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new ArrayIterator(array(1,2))));
    }

    public function testGetDatabaseTesters() {
        $dbConfigs = array();
        $builder =
            new Builder();
        $dbConfigs[] = $builder
            ->connection(
                 $this->createMock(
                     Connection::class))
            ->dataSet($this->dataSet)
            ->build();

        $builder =
            new Builder();
        $dbConfigs[] = $builder
            ->connection(
                $this->createMock(
                    Connection::class))
            ->dataSet($this->dataSet)
            ->build();
        
        $expected = array(
            new Tester($dbConfigs[0]),
            new Tester($dbConfigs[1]));
	
        $testCase = $this->getMockForAbstractClass(
            TestCase::class);
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
            Tester::class)
            ->disableOriginalConstructor()
            ->getMock();
        $testers[0]
            ->expects($this->once())
            ->method('onSetUp');

        $testers[] = $this->getMockBuilder(
            Tester::class)
            ->disableOriginalConstructor()
            ->getMock();
        $testers[1]
            ->expects($this->once())
            ->method('onSetUp');
 
        $testCase = $this->getMockForAbstractClass(
            TestCase::class);
        
        $testCase->setUpDatabaseTesters($testers);
    }

    public function testTearDownDatabaseTesters() {
        $testers = array();

        $testers[] = $this->getMockBuilder(
            Tester::class)
            ->disableOriginalConstructor()
            ->getMock();
        $testers[0]
            ->expects($this->once())
            ->method('onTearDown');

        $testers[] = $this->getMockBuilder(
            Tester::class)
            ->disableOriginalConstructor()
            ->getMock();
        $testers[1]
            ->expects($this->once())
            ->method('onTearDown');
 
        $testCase = $this->getMockForAbstractClass(
            TestCase::class);
        
        $testCase->tearDownDatabaseTesters($testers);
    }

    /**
     * @depends testGetDatabaseTesters
     */
    public function testSetUp($testCase) {
        $this->markTestSkipped("This test is flaky");
        $testCase->setUp();
        $this->assertObjectHasAttribute('testers', $testCase);
    }

    /**
     * @depends testGetDatabaseTesters
     */
    public function testTearDown($testCase) {
        $this->markTestSkipped("This test is flaky");
        $testCase->tearDown();
        $this->assertObjectHasAttribute('testers', $testCase);
    }

    public function testAssertTablesEqual() {
        $expected = new DefaultTable(
            new DefaultTableMetaData(
                'my_table',
                array()
            )
        );
        $actual = $expected;
        TestCase::assertTablesEqual($expected, $actual);
    }  

    public function testAssertDataSetsEqual() {
        $expected = new CompositeDataSet();
        $actual = $expected;
        TestCase::assertDataSetsEqual($expected, $actual);
    }  
}
