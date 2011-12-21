<?php

/**
 * Constraint for comparing strings while considering all whitespace to be equal.
 */
class PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace
extends PHPUnit_Framework_Constraint {

    private $expected;

    public function __construct($expected) {
        $this->expected = $expected;
    }

    public function evaluate($actual) {
        return $this->normalize($this->expected) == $this->normalize($actual);
    }

    private function normalize($string) {
        return implode(' ', preg_split('/\s+/', trim($string)));
    }

    protected function failureDescription($other, $description, $not) {
        return parent::failureDescription(
            $this->normalize($other), $description, $not);
    }

    public function toString() {
       return sprintf(
           'equals ignoring whitespace %s', 
           PHPUnit_Util_Type::export($this->normalize($this->expected)));
    }
}

