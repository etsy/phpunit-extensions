<?php

require_once 'PHPUnit/Extensions/Assert/More.php';
require_once 'PHPUnit/Extensions/Constraint/ArrayHasKeyValuePair.php';
require_once 'PHPUnit/Extensions/Constraint/StringMatchIgnoreWhitespace.php';

/**
 * @covers PHPUnit_Extensions_Constraint_HasItems
 */
class Tests_Extensions_Constraint_ArrayHasKeyValuePairTest
extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_null() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair(null, null);
        $constraint->evaluate(null);
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_nullExpected() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair(null, null);
        $constraint->evaluate(array(1, 2, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_nullActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(null);
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_empty() {
        $constraint = new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array());
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_emptyExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair(null, array());
        $constraint->evaluate(array(1, 2, 3));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_emptyActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array());
    }

    public function testEvaluate_equal() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array('key' => 'value'));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_keyMatchOnly() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'missing');
        $constraint->evaluate(array('key' => null));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_valueMatchOnly() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('WRONG', 'value');
        $constraint->evaluate(array('NO MATCH' => 'value'));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testEvaluate_existButNotPaired() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array('key' => 1, 'value'));
    }

    public function testEvaluate_multiDimensional() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', array(1, 2, 3));
        $constraint->evaluate(array('key' => array(1, 2, 3)));
    }

    public function testEvaluate_duplicateValue() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('key', 'value');
        $constraint->evaluate(array(1, 'value', 2, 'value', 'key' => 'value', 3));
    }

    public function testToString() {
        $constraint =
            new PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair('KEY', 'VALUE');
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            "has the key 'KEY' with the value 'VALUE'",
            $constraint->toString());
    }
}

