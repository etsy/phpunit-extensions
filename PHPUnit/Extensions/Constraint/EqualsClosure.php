<?php
use SebastianBergmann\Exporter\Exporter;

class PHPUnit_Extensions_Constraint_EqualsClosure
extends PHPUnit_Framework_Constraint {
    
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
