<?php
namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Exporter\Exporter;

/**
 * Determines whether or not the array contains the expected items.
 */
class HasItems extends Constraint {

    private $expected;
    protected $exporter;

    public function __construct($expected) {
        $this->expected = $expected;
	$this->exporter = new Exporter;
    }

    protected function matches($other): bool {
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

    public function toString(): string {
        return sprintf('has items %s', 
            $this->exporter->export($this->expected));
    }
}

