<?php

require_once 'PHPUnit/Extensions/Constraint/HasItems.php';

/**
 * @covers PHPUnit_Extensions_Constraint_HasItems
 */
class Tests_Extensions_Constraint_HasItemsTest 
extends PHPUnit_Framework_TestCase {

    public function testEvaluate_null() {
        $constraint = new PHPUnit_Extensions_Constraint_HasItems(null);
        $this->assertFalse($constraint->evaluate(null));
    }

    public function testEvaluate_nullExpected() {
        $constraint = new PHPUnit_Extensions_Constraint_HasItems(null);
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_nullActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(null));
    }

    public function testEvaluate_empty() {
        $constraint = new PHPUnit_Extensions_Constraint_HasItems(array());
        $this->assertTrue($constraint->evaluate(array()));
    }

    public function testEvaluate_emptyExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array());
        $this->assertTrue($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_emptyActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(array()));
    }

    public function testEvaluate_equal() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_sameDifferentOrder() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(3, 1, 2)));
    }

    public function testEvaluate_duplicateExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 1, 2, 2, 3, 3));
        $this->assertTrue($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_duplicateActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(1, 1, 2, 2, 3, 3)));
    }

    public function testEvaluate_moreThanExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(1, 0, 2, 3)));
    }

    public function testEvaluate_lessThanExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 0, 2, 3));
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testToString() {
        $constraint =
            new PHPUnit_Extensions_Constraint_HasItems(array(1, 2, 3));
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            'has items Array ( [0] => 1 [1] => 2 [2] => 3 )',
            $constraint->toString());
    }
}

