<?php

namespace PHPUnit\Extensions\MultipleDatabase;

use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\DataSet\ITable;

/**
 * TestCase the utilizes multiple database testers to connect and
 * populate multiple databases for tests dependent upon databases.
 */
abstract class TestCase 
extends \PHPUnit\Extensions\Mockery\TestCase {

    private $testers;

    protected function setUp() {
        parent::setUp();
        $this->testers = NULL;
        $this->setUpDatabaseTesters($this->getDatabaseTesters());
    }

    protected function tearDown() {
        $this->tearDownDatabaseTesters($this->getDatabaseTesters());

        $this->testers = NULL;
        parent::tearDown();
    } 
  
    /**
     * @return PHPUnit_Extensions_MultipleDatabase_Database array
     */
    protected abstract function getDatabaseConfigs();

    /**
     * Asserts that two given tables are equal.
     *
     * @param PHPUnit_Extensions_Database_DataSet_ITable $expected
     * @param PHPUnit_Extensions_Database_DataSet_ITable $actual
     * @param string $message
     */
    public static function assertTablesEqual($expected, $actual) {
        \PHPUnit\DbUnit\TestCase::assertTablesEqual($expected, $actual);
    }

/**
     * Asserts that two given datasets are equal.
     *
     * @param PHPUnit_Extensions_Database_DataSet_IDataSet $expected
     * @param PHPUnit_Extensions_Database_DataSet_IDataSet $actual
     * @param string $message
     */
    public static function assertDataSetsEqual($expected, $actual) {
        \PHPUnit\DbUnit\TestCase::assertDataSetsEqual($expected, $actual);
    }

    function setUpDatabaseTesters($testers) {
        foreach ($testers as $tester) {
            $tester->onSetUp();
        }
    }

    function tearDownDatabaseTesters($testers) {
        foreach ($testers as $tester) {
            $tester->onTearDown();
        }
    }

    function getDatabaseTesters() {
        if (!isset($this->testers)) {
            $this->testers = array();
            foreach ($this->getDatabaseConfigs() as $dbConfig) {
                $this->testers[] = new Tester($dbConfig);
            }
        }
        return $this->testers;
    }
}
