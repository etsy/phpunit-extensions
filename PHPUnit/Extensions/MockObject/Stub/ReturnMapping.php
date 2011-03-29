<?php

class PHPUnit_Extensions_MockObject_Stub_ReturnMapping
implements PHPUnit_Framework_MockObject_Stub {

    protected $return_map;

    /**
     * @param array $return_map an array of 
     * PHPUnit_Extensions_Mock_Object_Stub_ReturnMapping_Entry.
     */
    public function __construct(array $return_map) {
        $this->return_map = $return_map;
    }

    /**
     * @param PHPUnit_Framework_MockObject_Invocation $invocation
     * @return the invocation of the Entry with matching parameters.
     */
    public function invoke(PHPUnit_Framework_MockObject_Invocation $invocation) {
        foreach ($this->return_map as $entry) {
            if ($entry->matches($invocation)) {
                return $entry->invoke($invocation);
            }
        }
        PHPUnit_Framework_Assert::fail(
            sprintf(
                'No return value defined for %s', 
                PHPUnit_Util_Type::toString($invocation->parameters)));
    }

    public function toString() {
        return 'return result determined by returning the value ' .
            'mapped by the parameters of the invocation';
    }
}


