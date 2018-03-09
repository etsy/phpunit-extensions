<?php

namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\SameSize;
use SebastianBergmann\Exporter\Exporter;

/**
 * Determines whether or not the array contains the same exact contents,
 * but not necessarily the same order.
 */
class ArrayEqualsNoOrder extends LogicalAnd {
    private $andConstraint;
    protected $exporter; 
    public function __construct($expected) {
	$this->exporter = new Exporter;
        $this->setConstraints(
            array(
                new HasItems($expected),
                new SameSize($expected)
            )
        );
    }
}
