<?php

namespace PHPUnit\Extensions\MockObject\Stub\ReturnMapping;

use PHPUnit\Extensions\MockObject\Stub\ReturnMapping;

class Builder {

    private $entry_builders;
    private $default_entry;

    public function __construct() {
        $this->entry_builders = array();
        $this->default_entry = NULL;
    }

    /**
     * @return EntryBuilder
     */
    public function addEntry() {
        $entry_builder = 
            new EntryBuilder();
        $this->entry_builders[] = $entry_builder;
        return $entry_builder;
    }

    /**
     * Sets the default return value.
     *
     * @param PHPUnit_Extensions_MockObject_Stub|mixed $default the default
     * return value.
     * @return Builder
     */
    public function setDefault($default) {
        $entry_builder = new EntryBuilder();
        $entry_builder
            ->with($this->anything())
            ->will($default);

        $this->default_entry = $entry_builder->build();

        return $this;
    }

    /**
     * @return ReturnMapping
     */
    public function build() {
        $entries = array();
        foreach ($this->entry_builders as $builder) {
            $entries[] = $builder->build();
        }
        return new ReturnMapping(
            $entries,
            $this->default_entry
        );
    }
}

