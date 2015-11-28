<?php

use SebastianBergmann\Exporter\Exporter;

/**
 * Constraint for comparing strings while considering all whitespace to be equal.
 */
class PHPUnit_Extensions_Constraint_StringMatchIgnoreWhitespace
extends PHPUnit_Framework_Constraint {

    private $expected;
    protected $exporter;

    public function __construct($expected) {
        $this->expected = $expected;
	$this->exporter = new Exporter;
    }

    protected function matches($actual) {
        return $this->normalize($this->expected) == $this->normalize($actual);
    }

    private function normalize($string) {
        return implode(' ', preg_split('/\s+/', trim($string)));
    }

    public function toString() {
       return sprintf(
           'equals ignoring whitespace %s', 
           $this->exporter->export($this->normalize($this->expected)));
    }
}

