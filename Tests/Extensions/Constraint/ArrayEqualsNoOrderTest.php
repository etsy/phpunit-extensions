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

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_null() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(null);
        $constraint->evaluate(null);
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_nullExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(null);
        $constraint->evaluate(array(1, 2, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_nullActual() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        $constraint->evaluate(null);
    }

    public function testEvaluate_empty() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array()
        );
        $constraint->evaluate(array());
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_emptyExpected() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array()
        );
        $constraint->evaluate(array(1, 2, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_emptyActual() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        $constraint->evaluate(array());
    }

    public function testEvaluate_equal() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        $constraint->evaluate(array(1, 2, 3));
    }

    public function testEvaluate_sameDifferentOrder() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        $constraint->evaluate(array(3, 1, 2));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_duplicateExpected() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 1, 2, 2, 3, 3)
        );
        $constraint->evaluate(array(1, 2, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_duplicateActual() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        $constraint->evaluate(array(1, 1, 2, 2, 3, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_moreThanExpected() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        $constraint->evaluate(array(1, 0, 2, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_lessThanExpected() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 0, 2, 3)
        );
        $constraint->evaluate(array(1, 2, 3));
    }

    public function testToString() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            'has items Array ( 0 => 1 1 => 2 2 => 3 ) and count matches',
            $constraint->toString());
    }
}

