<?php
namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @covers StringMatchIgnoreWhitespace
 */
class StringMatchIgnoreWhitespaceTest extends TestCase {

    public function testEvaluate_trimExpected() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                ' trim ');
        $result = $constraint->evaluate('trim', '', true);
        $this->assertTrue($result);
    }

    public function testEvaluate_trimActual() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                'trim');
        $result = $constraint->evaluate(' trim ', '', true);
        $this->assertTrue($result);
    }

    public function testEvaluate_newlineExpected() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                "new\nline");
        $result = $constraint->evaluate('new line', '', true);
        $this->assertTrue($result);
    }

    public function testEvaluate_newlineActual() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                'new line');
        $result = $constraint->evaluate("new\nline", '', true);
        $this->assertTrue($result);
    }

    public function testEvaluate_equal() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                'are equal');
        $result = $constraint->evaluate('are equal', '', true);
        $this->assertTrue($result);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testEvaluate_notEqual() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                'not equal');
        $constraint->evaluate('are not equal');
    }

    public function testFailureDescription_throughAssert() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                "new\nline");
        try {
            $constraint->evaluate("\ttab");
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                "Failed asserting that '\ttab'"
                . ' equals ignoring whitespace \'new line\'.',
                $e->getMessage());
        }
    }

    public function testToString() {
        $constraint =
            new StringMatchIgnoreWhitespace(
                'expected string');
        $this->assertEquals(
             'equals ignoring whitespace \'expected string\'',
             $constraint->toString());
    }
}
