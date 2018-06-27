<?php
namespace PHPUnit\Extensions\MockObject\Stub;

use SebastianBergmann\Exporter\Exporter;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Stub;

class ReturnMapping
implements Stub {

    protected $return_map;
    protected $default;
    protected $exporter;
    /**
     * @param array $return_map an array of 
     * PHPUnit_Extensions_Mock_Object_Stub_ReturnMapping_Entry.
     * @param PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Entry $default
     * the default value to return in the event that no other parameters
     * matched.
     */
    public function __construct(
        array $return_map,
        $default = NULL
    ) {
        $this->return_map = $return_map;
        $this->default = $default;
	$this->exporter = new Exporter;
    }

    /**
     * @param Invocation $invocation
     * @return the invocation of the Entry with matching parameters.
     */
    public function invoke(Invocation $invocation) {

        foreach ($this->return_map as $entry) {
            if ($entry->matches($invocation)) {
                return $entry->invoke($invocation);
            }
        }

        if ($this->default != NULL) {
            return $this->default->invoke($invocation);
        }

        Assert::fail(
            sprintf(
                'No return value defined for %s', 
                $this->exporter->export($invocation->getParameters())));
    }

    public function toString() {
        return 'return result determined by returning the value ' .
            'mapped by the parameters of the invocation';
    }
}


