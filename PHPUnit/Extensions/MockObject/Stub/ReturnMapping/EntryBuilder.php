<?php

class PHPUnit_Extensions_MockObject_Stub_ReturnMapping_EntryBuilder {

    private $parameters;
    private $return;

    public function __construct() {}

    /**
     * @param array $parameters takes in an array of raw parameter values
     * and PHPUnit_Framework_Constraint
     * @return this
     */
    public function with(array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param $return takes in either a raw return value or a
     * PHPUnit_Extensions_MockObject_Stub
     * @return this
     */
    public function will($return) {
        $this->return = $return;
        return $this;
    }

    /**
     * @return PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Entry
     */
    public function build() {
        PHPUnit_Framework_Assert::assertNotNull(
            $this->parameters,
            "ReturnMapping cannot be used with null parameter list.");
        $parameter_matcher = 
            new PHPUnit_Framework_MockObject_Matcher_Parameters(
                $this->parameters);
        return new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Entry(
            $parameter_matcher, $this->return);
    }
}

