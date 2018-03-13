<?php

namespace PHPUnit\Extensions\MultipleDatabase;

use PHPUnit\DbUnit\DefaultTester;

/**
 * Database tester that uses 
 * a PHPUnit_Extensions_MultipleDataBase_DatabaseConfig.
 */
class Tester extends DefaultTester {

    /**
     * @param PHPUnit_Extensions_MultipleDatabase_DatabaseConfig
     */
    public function __construct($dbConfig) {
        parent::__construct($dbConfig->getConnection());
        $this->setDataSet($dbConfig->getDataSet());
        $this->setSetUpOperation($dbConfig->getSetUpOperation());
        $this->setTearDownOperation($dbConfig->getTearDownOperation());
    }
}

