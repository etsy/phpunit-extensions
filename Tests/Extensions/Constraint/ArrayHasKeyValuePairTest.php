<?php

namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\TestCase;
use PHPUnit\Extensions\Assert\More;

/**
 * @covers HasItems
 */
class ArrayHasKeyValuePairTest extends TestCase {
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_null() {
        $constraint = new ArrayHasKeyValuePair(null, null);
        $constraint->evaluate(null);
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_nullExpected() {
        $constraint = new ArrayHasKeyValuePair(null, null);
        $constraint->evaluate(array(1, 2, 3));
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_nullActual() {
        $constraint =
            new ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(null);
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_empty() {
        $constraint = new ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array());
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_emptyExpected() {
        $constraint =
            new ArrayHasKeyValuePair(null, array());
        $constraint->evaluate(array(1, 2, 3));
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_emptyActual() {
        $constraint =
            new ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array());
    }
    public function testEvaluate_equal() {
        $constraint =
            new ArrayHasKeyValuePair('key', 'value');
        $result = $constraint->evaluate(array('key' => 'value'), '', true);
        $this->assertTrue($result);
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_keyMatchOnly() {
        $constraint =
            new ArrayHasKeyValuePair('key', 'missing');
        $constraint->evaluate(array('key' => null));
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_valueMatchOnly() {
        $constraint =
            new ArrayHasKeyValuePair('WRONG', 'value');
        $constraint->evaluate(array('NO MATCH' => 'value'));
    }
    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_existButNotPaired() {
        $constraint =
            new ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array('key' => 1, 'value'));
    }
    public function testEvaluate_multiDimensional() {
        $constraint =
            new ArrayHasKeyValuePair('key', array(1, 2, 3));
        $result = $constraint->evaluate(array('key' => array(1, 2, 3)), '', true);
        $this->assertTrue($result);
    }
    public function testEvaluate_duplicateValue() {
        $constraint =
            new ArrayHasKeyValuePair('key', 'value');
        $result = $constraint->evaluate(array(1, 'value', 2, 'value', 'key' => 'value', 3), '', true);
        $this->assertTrue($result);
    }
    public function testToString() {
        $constraint =
            new ArrayHasKeyValuePair('KEY', 'VALUE');
        More::assertStringMatchIgnoreWhitespace(
            "has the key 'KEY' with the value 'VALUE'",
            $constraint->toString());
    }
}
