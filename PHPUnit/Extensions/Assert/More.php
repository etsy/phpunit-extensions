<?php

/**
 * A collection of extra asserts.
 */
class PHPUnit_Extensions_Assert_More {

    private function __construct() {
       // Static library
    }

    /**
     * Asserts that the actual array contains at least one occurence
     * of each of the values in the expected array.
     *
     * @param array $expected
     * @param array $actual
     * @param $message
     */
    public static function assertHasItems($expected, $actual, $message='') {
        PHPUnit_Framework_Assert::assertThat(
            $actual,
            new PHPUnit_Extensions_Constraint_HasItems($expected),
            $message);
    }

    /**
     * Asserts that the two arrays contain the same exact contents,
     * but are not necessarily the same order.
     * 
     * @param array $expected
     * @param array $actual
     * @param string $message
     */
    public static function assertArrayEqualsNoOrder(
        $expected, $actual, $message='') {

        PHPUnit_Framework_Assert::assertThat(
            $actual,
            new PHPUnit_Extensions_Constraint_ArrayEqualsNoOrder($expected),
            $message);
    }

    /**
     * Asserts that the two strings match but that all whitespaces are 
     * not necessarily equal.
     *
     * @param array $expected
     * @param array $actual
     * @param string $message
     */
    public static function assertStringMatchIgnoreWhitespace(
        $expected, $actual, $message='') {

        PHPUnit_Framework_Assert::assertThat(
            $actual,
            new PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace($expected),
            $message);
    }
}

