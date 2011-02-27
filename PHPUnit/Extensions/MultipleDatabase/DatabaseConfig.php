<?php

/**
 * Configuration for a data base configuration.
 */
class PHPUnit_Extensions_DBunit_MultipleDatabase_DatabaseConfig {

    private $connection;
    private $dataSet;
    private $setUpOperation;
    private $tearDownOperation;

    public function __construct($connection, $dataSet, $setUpOperation, $tearDownOperation) {
        $this->connection = $connection;
        $this->dataSet = $dataSet;
        $this->setUpOperation = $setUpOperation;
        $this->tearDownOperation = $tearDownOperation;
    } 

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet() {
        return $this->dataSet;
    }

    /**
     * @return PHPUnit_Extensions_Database_Operation_IDatabaseOperation
     */
    public function getSetUpOperation() {
        return $this->setUpOperation;
    }

    /**
     * @return PHPUnit_Extensions_Database_Operation_IDatabaseOperation
     */
    public function getTearDownOperation() {
        return $this->tearDownOperation;
    }
}
