<?php
namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Exporter\Exporter;

/**
 * Constraint for comparing strings while considering all whitespace to be equal.
 */
class StringMatchIgnoreWhitespace extends Constraint {

    private $expected;
    protected $exporter;

    public function __construct($expected) {
        $this->expected = $expected;
	$this->exporter = new Exporter;
    }

    protected function matches($actual): bool {
        return $this->normalize($this->expected) == $this->normalize($actual);
    }

    private function normalize($string) {
        /**
         * the extra replace is because exporter started putting
         * the identifiers next to the array name
         * like so:
         * Array &0 (
         *  Array &1 (
         *  ....
         * which we  previously didn't expect
         */
        return preg_replace('#\&. #','', implode(' ', preg_split('/\s+/', trim($string))));
    }

    public function toString(): string {

       return sprintf(
           'equals ignoring whitespace %s', 
           $this->exporter->export($this->normalize($this->expected)));
    }
}

