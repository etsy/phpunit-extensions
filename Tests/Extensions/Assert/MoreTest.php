<?php

require_once 'PHPUnit/Extensions/Assert/More.php';
require_once 'PHPUnit/Extensions/Constraint/ArrayEqualsNoOrder.php';
require_once 'PHPUnit/Extensions/Constraint/HasItems.php';
require_once 'PHPUnit/Extensions/Constraint/StringMatchIgnoreWhitespace.php';

/**
 * @covers PHPUnit_Extensions_Assert_More
 */
class Tests_Extensions_Assert_MoreTest extends PHPUnit_Framework_TestCase {

    public function testAssertHasItems_passes() {
        PHPUnit_Extensions_Assert_More::assertHasItems(
            array(1, 2, 3),
            array(3, 1, 0, 2));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testAssertHasItems_fails() {
        PHPUnit_Extensions_Assert_More::assertHasItems(
            array(4),
            array(1, 2, 3));
    }

    public function testAssertArrayEqualsNoOrder_passes() {
        PHPUnit_Extensions_Assert_More::assertArrayEqualsNoOrder(
            array(1, 2, 3), 
            array(3, 1, 2));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testAssertArrayEqualsNoOrder_sameSize() {
        PHPUnit_Extensions_Assert_More::assertArrayEqualsNoOrder(
            array(1, 2, 3),
            array(4, 5, 6));
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testAssertArrayEqualsNoOrder_duplicates() {
        PHPUnit_Extensions_Assert_More::assertArrayEqualsNoOrder(
            array(1, 2, 3),
            array(1, 1, 2, 3));
    }

    public function testAssertStringMatchIgnoreWhitespace_passes() {
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            "spaces do not matter",
            "spaces\ndo\tnot matter ");
    }

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testAssertStringMatchIgnoreWhitespace_fails() {
        PHPUnit_Extensions_Assert_More::assertStringMatchIgnoreWhitespace(
            "spaces do not matter",
            "but the not whitespace characters do!");
    }
}

