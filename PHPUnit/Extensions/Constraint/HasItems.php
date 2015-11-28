<?php

use SebastianBergmann\Exporter\Exporter;

/**
 * Determines whether or not the array contains the expected items.
 */
class PHPUnit_Extensions_Constraint_HasItems 
extends PHPUnit_Framework_Constraint {

    private $expected;
    protected $exporter;

    public function __construct($expected) {
        $this->expected = $expected;
	$this->exporter = new Exporter;
    }

    protected function matches($other) {
        if (!(is_array($this->expected) && is_array($other))) {
            return false;
        }

        foreach ($this->expected as $item) {
            if (!in_array($item, $other)) {
                return false;
            }
        }

        return true;
    }

    public function toString() {
        return sprintf('has items %s', 
            $this->exporter->export($this->expected));
    }
}

