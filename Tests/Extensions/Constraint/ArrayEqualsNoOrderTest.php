<?php

require_once 'PHPUnit/Extensions/Constraint/ArrayEqualsNoOrder.php';

/**
 * @covers PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder
 */
class Tests_Extensions_Constraint_ArrayEqualsNoOrderTest
extends PHPUnit_Framework_TestCase {

    private function createConstraint($expected) {
       return new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder($expected) ;
    }

    public function testEvaluate_null() {
        $constraint = $this->createConstraint(null);
        $this->assertFalse($constraint->evaluate(null));
    }

    public function testEvaluate_nullExpected() {
        $constraint = $this->createConstraint(null);
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_nullActual() {
        $constraint =  $this->createConstraint(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(null));
    }

    public function testEvaluate_empty() {
        $constraint = $this->createConstraint(array());
        $this->assertTrue($constraint->evaluate(array()));
    }

    public function testEvaluate_emptyExpected() {
        $constraint = $this->createConstraint(array());
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_emptyActual() {
        $constraint = $this->createConstraint(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(array()));
    }

    public function testEvaluate_equal() {
        $constraint = $this->createConstraint(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_sameDifferentOrder() {
        $constraint = $this->createConstraint(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(3, 1, 2)));
    }

    public function testEvaluate_duplicateExpected() {
        $constraint = $this->createConstraint(array(1, 1, 2, 2, 3, 3));
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_duplicateActual() {
        $constraint = $this->createConstraint(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(array(1, 1, 2, 2, 3, 3)));
    }

    public function testEvaluate_moreThanExpected() {
        $constraint = $this->createConstraint(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(array(1, 0, 2, 3)));
    }

    public function testEvaluate_lessThanExpected() {
        $constraint = $this->createConstraint(array(1, 0, 2, 3));
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testToString() {
        $constraint = $this->createConstraint(array(1, 2, 3));
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            'has items Array ( [0] => 1 [1] => 2 [2] => 3 ) and is same size as Array ( [0] => 1 [1] => 2 [2] => 3 )',
            $constraint->toString());
    }
}

