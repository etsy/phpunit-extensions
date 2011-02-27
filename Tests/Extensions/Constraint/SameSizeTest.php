<?php

require_once 'PHPUnit/Extensions/Constraint/SameSize.php';

/**
 * @covers PHPUnit_Extensions_Constraint_SameSize
 */
class Tests_Extensions_Constraint_SameSizeTest
extends PHPUnit_Framework_TestCase {

    public function testEvaluate_null() {
        $constraint = new PHPUnit_Extensions_Constraint_SameSize(null);
        $this->assertFalse($constraint->evaluate(null));
    }

    public function testEvaluate_nullExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(null);
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_nullActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(null));
    }

    public function testEvaluate_empty() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array());
        $this->assertTrue($constraint->evaluate(array()));
    }

    public function testEvaluate_emptyExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array());
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_emptyActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(array()));
    }

    public function testEvaluate_sameSize() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array(1, 2, 3));
        $this->assertTrue($constraint->evaluate(array(4, 5, 6)));
    }

    public function testEvaluate_smallerThanExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array(1, 2, 3, 4, 5));
        $this->assertFalse($constraint->evaluate(array(1, 2, 3)));
    }

    public function testEvaluate_largerThanExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array(1, 2, 3));
        $this->assertFalse($constraint->evaluate(array(5, 4, 3, 2, 1)));
    }

    public function testToString() {
        $constraint =
            new PHPUnit_Extensions_Constraint_SameSize(array(1, 2, 3));
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            'is same size as Array ( [0] => 1 [1] => 2 [2] => 3 )',
            $constraint->toString());
    }
}

