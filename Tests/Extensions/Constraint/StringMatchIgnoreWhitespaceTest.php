<?php

require_once 'PHPUnit/Extensions/Constraint/StringMatchIgnoreWhitespace.php';

/**
 * @covers PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace
 */
class Tests_Extensions_Constraint_StringMatchIgnoreWhitespaceTest
extends PHPUnit_Framework_TestCase {

    public function testEvaluate_trimExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                ' trim ');
        $this->assertTrue($constraint->evaluate('trim'));
    }

    public function testEvaluate_trimActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                'trim');
        $this->assertTrue($constraint->evaluate(' trim '));
    }

    public function testEvaluate_newlineExpected() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                "new\nline");
        $this->assertTrue($constraint->evaluate('new line'));
    }

    public function testEvaluate_newlineActual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                'new line');
        $this->assertTrue($constraint->evaluate("new\nline"));
    }

    public function testEvaluate_equal() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                'are equal');
        $this->assertTrue($constraint->evaluate('are equal'));
    }

    public function testEvaluate_notEqual() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                'not equal');
        $this->assertFalse($constraint->evaluate('are not equal'));
    }

    public function testFailureDescription_throughAssert() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                "new\nline");
        try {
            $constraint->evaluate("\ttab");
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals(
                'Failed asserting that <string:tab>'
                . ' equals ignoring whitespace <string:new line>.',
                $e->getMessage());
        }
    }

    public function testToString() {
        $constraint =
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace(
                'expected string');
        $this->assertEquals(
             'equals ignoring whitespace <string:expected string>',
             $constraint->toString());
    }
}
