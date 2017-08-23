<?php
namespace PHPUnit\Extensions\MultipleDatabase\DatabaseConfig;

use PHPUnit\DbUnit\Operation\Factory\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\Operation\Operation;
use PHPUnit\DbUnit\Operation\Factory;

use PHPUnit\Extensions\MultipleDatabase\DatabaseConfig;

use Exception;

/**
 * Builder for a DatabaseConfig.
 */
class Builder {

    private $connection;
    private $dataSet;
    private $setUpOperation;
    private $tearDownOperation;

    public function __construct() {
        $this->setUpOperation = 
            Factory::CLEAN_INSERT();
        $this->tearDownOperation = 
            Factory::TRUNCATE();
    }

    /**
     * A connection must be provided.
     *
     * @param Connection
     * 
     * @return Builder
     */
    public function connection($connection) {
        $this->connection = $connection;
        return $this;
    }

    /**
     * A data set must be provided.
     *
     * @param IDataSet $dataSet
     * 
     * @return Builder
     */
    public function dataSet($dataSet) {
        $this->dataSet = $dataSet;
        return $this;
    }

    /**
     * Default set up operation is CLEAN_INSERT with cascading deletes.
     *
     * @param Operation $setUpOperation
     * 
     * @return Builder
     */
    public function setUpOperation($setUpOperation) {
        $this->setUpOperation = $setUpOperation;
        return $this;
    }

    /**
     * Default tear down operation is NONE.
     *
     * @param Operation $tearDownOperation
     * 
     * @return Builder
     */
    public function tearDownOperation($tearDownOperation) {
        $this->tearDownOperation = $tearDownOperation;
        return $this;
    }

    /**
     * @return Builder
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
        return new DatabaseConfig(
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

