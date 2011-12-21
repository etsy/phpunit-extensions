<?php

/**
 * Determines whether or not the array contains the same exact contents,
 * but not necessarily the same order.
 */
class PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder
extends PHPUnit_Framework_Constraint {

    private $andConstraint;

    public function __construct($expected) {
        $this->andConstraint = new PHPUnit_Framework_Constraint_And();
        $this->andConstraint->setConstraints(
            array(
                new PHPUnit_Extensions_Constraint_HasItems($expected),
                new PHPUnit_Extensions_Constraint_SameSize($expected)
            )
        );
    }

    protected function matches($other) {
        return $this->andConstraint->matches($other);
    }

    public function toString() {
        return $this->andConstraint->toString();
    }
}

