<?php

class PHPUnit_Extensions_MockObject_Stub_ReturnMapping
implements PHPUnit_Framework_MockObject_Stub {

    protected $return_map;
    protected $default;

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

        if ($this->default != NULL) {
            return $this->default->invoke($invocation);
        }

        PHPUnit_Framework_Assert::fail(
            sprintf(
                'No return value defined for %s', 
                PHPUnit_Util_Type::export($invocation->parameters)));
    }

    public function toString() {
        return 'return result determined by returning the value ' .
            'mapped by the parameters of the invocation';
    }
}


