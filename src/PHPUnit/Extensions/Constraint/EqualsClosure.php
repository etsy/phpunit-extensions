<?php
namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Exporter\Exporter;

class EqualsClosure
extends Constraint {
    
    private $expected;
    private $closure;
    protected $exporter;

    public function __construct($expected, $closure) {
        $this->expected = $expected;
        $this->closure = $closure;
	$this->exporter = new Exporter;
    }

    protected function matches($actual) {
        return call_user_func_array(
            $this->closure,
            array($this->expected, $actual)
        );
    }

    public function toString() {
        return sprintf('is equal to %s', 
            $this->exporter->export($this->expected)
        );
    }
}
