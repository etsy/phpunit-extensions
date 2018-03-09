<?php

namespace PHPUnit\Extensions\Assert;

use PHPUnit\Framework\TestCase;

/**
 * @covers More
 */
class MoreTest extends TestCase {

    public function testAssertHasItems_passes() {
        More::assertHasItems(
            array(1, 2, 3),
            array(3, 1, 0, 2));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertArrayHasKeyValuePair_fails() {
        More::assertArrayHasKeyValuePair(
            'key',
            'value',
            array(1, 2, 3));
    }

    public function testAssertArrayHasKeyValuePair_passes() {
        More::assertArrayHasKeyValuePair(
            'key',
            'value',
            array('a', 3, 'key' => 'value'));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertHasItems_fails() {
        More::assertHasItems(
            array(4),
            array(1, 2, 3));
    }

    public function testAssertArrayEqualsNoOrder_passes() {
        More::assertArrayEqualsNoOrder(
            array(1, 2, 3), 
            array(3, 1, 2));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertArrayEqualsNoOrder_sameSize() {
        More::assertArrayEqualsNoOrder(
            array(1, 2, 3),
            array(4, 5, 6));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertArrayEqualsNoOrder_duplicates() {
        More::assertArrayEqualsNoOrder(
            array(1, 2, 3),
            array(1, 1, 2, 3));
    }

    public function testAssertStringMatchIgnoreWhitespace_passes() {
        More::assertStringMatchIgnoreWhitespace(
            "spaces do not matter",
            "spaces\ndo\tnot matter ");
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testAssertStringMatchIgnoreWhitespace_fails() {
        More::assertStringMatchIgnoreWhitespace(
            "spaces do not matter",
            "but the not whitespace characters do!");
    }
}

