<?php

class PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder {

    private $entry_builders;

    public function __construct() {
        $this->entry_builders = array();
    }

    /**
     * @return PHPUnit_Extensions_MockObject_Stub_ReturnMapping_EntryBuilder
     */
    public function addEntry() {
        $entry_builder = 
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_EntryBuilder();
        $this->entry_builders[] = $entry_builder;
        return $entry_builder;
    }

    /**
     * @return PHPUnit_Extensions_MockObject_Stub_ReturnMapping
     */
    public function build() {
        $entries = array();
        foreach ($this->entry_builders as $builder) {
            $entries[] = $builder->build();
        }
        return new PHPUnit_Extensions_MockObject_Stub_ReturnMapping($entries);
    }
}

