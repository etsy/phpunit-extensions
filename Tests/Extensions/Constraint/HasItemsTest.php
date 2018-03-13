<?php

namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\TestCase;
use PHPUnit\Extensions\Assert\More;

/**
 * @covers HasItems
 */
class HasItemsTest extends TestCase {
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_null() {
        $constraint = new HasItems(null);
        $constraint->evaluate(null);
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_nullExpected() {
        $constraint = new HasItems(null);
        $constraint->evaluate(array(1, 2, 3));
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_nullActual() {
        $constraint =
            new HasItems(array(1, 2, 3));
        $constraint->evaluate(null);
    }
    public function testEvaluate_empty() {
        $constraint = new HasItems(array());
        $result = $constraint->evaluate(array(), '', true);
        $this->assertTrue($result);
    }
    public function testEvaluate_emptyExpected() {
        $constraint =
            new HasItems(array());
        $result = $constraint->evaluate(array(1, 2, 3), '', true);
        $this->assertTrue($result);
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_emptyActual() {
        $constraint =
            new HasItems(array(1, 2, 3));
        $constraint->evaluate(array());
    }
    public function testEvaluate_equal() {
        $constraint =
            new HasItems(array(1, 2, 3));
        $result = $constraint->evaluate(array(1, 2, 3), '', true);
        $this->assertTrue($result);
    }
    public function testEvaluate_sameDifferentOrder() {
        $constraint =
            new HasItems(array(1, 2, 3));
        $result = $constraint->evaluate(array(3, 1, 2), '', true);
        $this->assertTrue($result);
    }
    public function testEvaluate_duplicateExpected() {
        $constraint =
            new HasItems(array(1, 1, 2, 2, 3, 3));
        $result = $constraint->evaluate(array(1, 2, 3), '', true);
        $this->assertTrue($result);
    }
    public function testEvaluate_duplicateActual() {
        $constraint =
            new HasItems(array(1, 2, 3));
        $result = $constraint->evaluate(array(1, 1, 2, 2, 3, 3), '', true);
        $this->assertTrue($result);
    }
    public function testEvaluate_moreThanExpected() {
        $constraint =
            new HasItems(array(1, 2, 3));
        $result = $constraint->evaluate(array(1, 0, 2, 3), '', true);
        $this->assertTrue($result);
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_lessThanExpected() {
        $constraint = new HasItems(array(1, 0, 2, 3));
        $constraint->evaluate(array(1, 2, 3));
    }
    public function testToString() {
        $constraint = new HasItems(array(1, 2, 3));
        More::assertStringMatchIgnoreWhitespace(
            'has items Array ( 0 => 1 1 => 2 2 => 3 )',
            $constraint->toString());
    }
}          
