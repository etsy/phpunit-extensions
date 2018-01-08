<?php
namespace PHPUnit\Extensions\MockObject\Stub\ReturnMapping;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\Matcher\Parameters;

class EntryBuilder {

    private $parameters;
    private $return;

    public function __construct() {}

    /**
     * @param array $parameters takes in an array of raw parameter values
     * and PHPUnit_Framework_Constraint
     * 
     * @return EntryBuilder
     */
    public function with(array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param $return takes in either a raw return value or a
     * PHPUnit_Extensions_MockObject_Stub
     * 
     * @return EntryBuilder
     */
    public function will($return) {
        $this->return = $return;
        return $this;
    }

    /**
     * @return Entry
     */
    public function build() {
        Assert::assertNotNull(
            $this->parameters,
            "ReturnMapping cannot be used with null parameter list.");
        $parameter_matcher = 
            new Parameters(
                $this->parameters);
        return new Entry(
            $parameter_matcher, $this->return);
    }
}

