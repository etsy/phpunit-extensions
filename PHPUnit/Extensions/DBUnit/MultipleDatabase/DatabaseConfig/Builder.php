<?php

/**
 * Builder for a DatabaseConfig.
 */
class PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder {

    private $connection;
    private $dataSet;
    private $setUpOperation;
    private $tearDownOperation;

    public function __construct() {
        $this->setUpOperation = 
            PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT();
        $this->tearDownOperation = 
            PHPUnit_Extensions_Database_Operation_Factory::TRUNCATE();
    }

    /**
     * A connection must be provided.
     *
     * @param PHPUnit_Extensions_Database_DB_IDatabaseConnection connection
     * @return PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder
     */
    public function connection($connection) {
        $this->connection = $connection;
        return $this;
    }

    /**
     * A data set must be provided.
     *
     * @param PHPUnit_Extensions_Database_DataSet_IDataSet dataSet
     * @return PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder
     */
    public function dataSet($dataSet) {
        $this->dataSet = $dataSet;
        return $this;
    }

    /**
     * Default set up operation is CLEAN_INSERT with cascading deletes.
     *
     * @param PHPUnit_Extensions_Database_Operation_IDatabaseOperation setUpOperation
     * @return PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder
     */
    public function setUpOperation($setUpOperation) {
        $this->setUpOperation = $setUpOperation;
        return $this;
    }

    /**
     * Default tear down operation is NONE.
     *
     * @param PHPUnit_Extensions_Database_Operation_IDatabaseOperation tearDownOperation
     * @return PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder
     */
    public function tearDownOperation($tearDownOperation) {
        $this->tearDownOperation = $tearDownOperation;
        return $this;
    }

    /**
     * @return PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig_Builder
     */
    public function build() {
        if (!isset($this->connection)) {
            throw new Exception("Must provide a connection.");
        }
        if (!isset($this->dataSet)) {
            throw new Exception("Must provide a data set.");
        }
        
        if($this->datasetIsEmpty()) {
            throw new Exception("Empty dataset (is the path correct?)");
        }
        return new PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig(
            $this->connection,
            $this->dataSet,
            $this->setUpOperation,
            $this->tearDownOperation);
    }

    private function datasetIsEmpty() {
        foreach($this->dataSet->getIterator() as $x) {
            return false;
        }
        return true;
    }

}

