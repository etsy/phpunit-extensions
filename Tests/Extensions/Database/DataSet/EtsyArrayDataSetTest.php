<?php

namespace PHPUnit\Extensions\Database\DataSet;

use PHPUnit\Framework\TestCase;

use PHPUnit\DbUnit\DataSet\DefaultTable;
use PHPUnit\DbUnit\DataSet\DefaultTableMetaData;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use PHPUnit\DbUnit\Constraint\TableIsEqual;
use PHPUnit\DbUnit\DataSet\ITableMetadata;

class EtsyArrayDataSetTest extends TestCase {
    private $data_set;
    protected function setUp() {
        parent::setUp();
        $this->data_set = new EtsyArrayDataSet(
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
        $expected = new DefaultTable(
            new DefaultTableMetaData(
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
            new TableIsEqual($expected)
        );
    }
    public function testGetTable_b() {
        $expected = new DefaultTable(
            new DefaultTableMetaData(
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
            new TableIsEqual($expected)
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
        $data_set = new EtsyArrayDataSet(
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
                new DefaultDataSet()
            )
        );
    }
    public function testEmptyTable() {
        $tables = array(
            'my_table' => new DefaultTable(
                new DefaultTableMetaData(
                    'my_table',
                    array()
                )
            )
        );
        $data_set = new EtsyArrayDataSet(
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
        $data_set = new EtsyArrayDataSet(
            array(),
            $tables
        );
        $this->assertEquals(array('my_table'), $data_set->getTableNames());
    }
    /**
     * @depends testEmptyTable
     */
    public function testEmptyTable_getTableMetaData($tables) {
        $data_set = new EtsyArrayDataSet(
            array(),
            $tables
        );
        $meta_data = $data_set->getTableMetaData('my_table');
        $this->assertInstanceOf(ITableMetadata::class,
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
