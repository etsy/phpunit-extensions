<?php

/**
 * Database tester that uses 
 * a PHPUnit_Extensions_DBUnit_MultipleDataBase_DatabaseConfig.
 */
class PHPUnit_Extensions_DBUnit_MultipleDatabase_Tester 
extends PHPUnit_Extensions_Database_DefaultTester {

    /**
     * @param PHPUnit_Extensions_DBUnit_MultipleDatabase_DatabaseConfig
     */
    public function __construct($dbConfig) {
        parent::__construct($dbConfig->getConnection());
        $this->setDataSet($dbConfig->getDataSet());
        $this->setSetUpOperation($dbConfig->getSetUpOperation());
        $this->setTearDownOperation($dbConfig->getTearDownOperation());
    }
}
