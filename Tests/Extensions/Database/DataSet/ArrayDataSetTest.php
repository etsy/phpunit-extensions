<?php

require_once 'PHPUnit/Extensions/Database/DataSet/ArrayDataSet.php';

class Tests_Extensions_Database_DataSet_ArrayDataSetTest
extends PHPUnit_Framework_TestCase {

    private $data_set;

    protected function setUp() {
        parent::setUp();
        $this->data_set = new PHPUnit_Extensions_Database_DataSet_ArrayDataSet(
            array(
                'table_a' => array(
                    array(
                        'id' => 1,
                        'first' => 'bob',
                        'last' => 'johnson',
                    ),
                    array(
                        'id' => 2,
                        'first' => 'sally',
                        'last' => 'mae',
                        'age' => 29,
                    ),
                ),
                'table_b' => array(
                    array(
                        'id' => 1,
                        'pet' => 'dog',
                    ),
                    array(
                        'id' => 2,
                        'pet' => 'cat',
                    ),
                ),
            )
        );
    }

    public function testGetTable_a() {
        $expected = new PHPUnit_Extensions_Database_DataSet_DefaultTable(
            new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData(
                'table_a', array('id', 'first', 'last', 'age')
            )
        );
        $expected->addRow(
            array(
                'id' => 1,
                'first' => 'bob',
                'last' => 'johnson',
            )
        );
        $expected->addRow(
            array(
                'id' => 2,
                'first' => 'sally',
                'last' => 'mae',
                'age' => 29,
            )
        );

        $this->assertThat(
            $this->data_set->getTable('table_a'),
            new PHPUnit_Extensions_Database_Constraint_TableIsEqual($expected)
        );
    }

    public function testGetTable_b() {
        $expected = new PHPUnit_Extensions_Database_DataSet_DefaultTable(
            new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData(
                'table_b', array('id', 'pet')
            )
        );
        $expected->addRow(
            array(
                'id' => 1,
                'pet' => 'dog',
            )
        );
        $expected->addRow(
            array(
                'id' => 2,
                'pet' => 'cat',
            )
        );

        $this->assertThat(
            $this->data_set->getTable('table_b'),
            new PHPUnit_Extensions_Database_Constraint_TableIsEqual($expected)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage my_table is not a table in the current database
     */
    public function testGetTable_doesNotExist() {
        $this->data_set->getTable('my_table');
    }

    public function testEmpty() {
        $data_set = new PHPUnit_Extensions_Database_DataSet_ArrayDataSet(
            array()
        );
        $this->assertNotNull($data_set);
        return $data_set;
    }

    /**
     * @depends testEmpty
     */
    public function testEmpty_getTableNames($data_set) {
        $this->assertEmpty($data_set->getTableNames());
    }

    /**
     * @depends testEmpty
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage my_table is not a table in the current database
     */
    public function testEmpty_getTableMetaData($data_set) {
        $data_set->getTableMetaData('my_table');
    }

    /**
     * @depends testEmpty
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage my_table is not a table in the current database
     */
    public function testEmpty_getTable($data_set) {
        $data_set->getTable('my_table');
    }

    /**
     * @depends testEmpty
     */
    public function testEmpty_getReverseIterator($data_set) {
        $this->assertFalse(
            $data_set->getReverseIterator()->valid()
        );
    }

    /**
     * @depends testEmpty
     */
    public function testEmpty_matches($data_set) {
        $this->assertTrue(
            $data_set->matches(
                new PHPUnit_Extensions_Database_DataSet_DefaultDataSet()
            )
        );
    }

    public function testEmptyTable() {
        $tables = array(
            'my_table' => new PHPUnit_Extensions_Database_DataSet_DefaultTable(
                new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData(
                    'my_table',
                    array()
                )
            )
        );
        $data_set = new PHPUnit_Extensions_Database_DataSet_ArrayDataSet(
            array(),
            $tables
        );
        $this->assertNotNull($data_set);
        return $tables;
    }

    /**
     * @depends testEmptyTable
     */
    public function testEmptyTable_getTableNames($tables) {
        $data_set = new PHPUnit_Extensions_Database_DataSet_ArrayDataSet(
            array(),
            $tables
        );
        $this->assertEquals(array('my_table'), $data_set->getTableNames());
    }

    /**
     * @depends testEmptyTable
     */
    public function testEmptyTable_getTableMetaData($tables) {
        $data_set = new PHPUnit_Extensions_Database_DataSet_ArrayDataSet(
            array(),
            $tables
        );
        $meta_data = $data_set->getTableMetaData('my_table');
        $this->assertInstanceOf(
            'PHPUnit_Extensions_Database_DataSet_ITableMetaData',
            $meta_data
        );
        return $meta_data;
    }

    /**
     * @depends testEmptyTable_getTableMetaData
     */
    public function testEmptyTable_getTableMetaData_getColumns($meta_data) {
        $this->assertEmpty($meta_data->getColumns());
    }

    /**
     * @depends testEmptyTable_getTableMetaData
     */
    public function testEmptyTable_getTableMetaData_getPrimaryKeys($meta_data) {
        $this->assertEmpty($meta_data->getPrimaryKeys());
    }

    /**
     * @depends testEmptyTable_getTableMetaData
     */
    public function testEmptyTable_getTableMetaData_getTableName($meta_data) {
        $this->assertEquals('my_table', $meta_data->getTableName());
    }
}