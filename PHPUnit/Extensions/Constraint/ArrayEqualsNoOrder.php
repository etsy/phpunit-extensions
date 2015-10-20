<?php

use SebastianBergmann\Exporter\Exporter;

/**
 * Determines whether or not the array contains the same exact contents,
 * but not necessarily the same order.
 */
class PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder
extends PHPUnit_Framework_Constraint_And {

    private $andConstraint;
    protected $exporter; 

    public function __construct($expected) {

	$this->exporter = new Exporter;
        $this->setConstraints(
            array(
                new PHPUnit_Extensions_Constraint_HasItems($expected),
                new PHPUnit_Framework_Constraint_SameSize($expected)
            )
        );
    }
}

