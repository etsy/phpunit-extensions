<?php

/**
 * Determines whether or not two arrays are the same size. 
 */
class PHPUnit_Extensions_Constraint_SameSize 
extends PHPUnit_Framework_Constraint {

    private $expected;

    public function __construct($expected) {
        $this->expected = $expected;
    }

    public function evaluate($other) {
        return is_array($this->expected) 
            && is_array($other) 
            && (count($this->expected) == count($other)); 
    }

    public function toString() {
        return sprintf(
            'is same size as %s', 
            PHPUnit_Util_Type::export($this->expected)
        );
    }
}

