<?php

require_once 'PHPUnit/Extensions/Assert/More.php';
require_once 'PHPUnit/Extensions/Constraint/HasItems.php';
require_once 'PHPUnit/Extensions/Constraint/ArrayEqualsNoOrder.php';
require_once 'PHPUnit/Extensions/Constraint/StringMatchIgnoreWhitespace.php';

/**
 * @covers PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder
 */
class Tests_Extensions_Constraint_ArrayEqualsNoOrderTest
extends PHPUnit_Framework_TestCase {


    public function testToString() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            'has items Array ( 0 => 1 1 => 2 2 => 3 ) and count matches 3',
            $constraint->toString());
    }
}

