<?php

require_once 'PHPUnit/Extensions/MockObject/Stub/ReturnMapping.php';
require_once 'PHPUnit/Extensions/MockObject/Stub/ReturnMapping/Builder.php';
require_once 'PHPUnit/Extensions/MockObject/Stub/ReturnMapping/Entry.php';
require_once 'PHPUnit/Extensions/MockObject/Stub/ReturnMapping/EntryBuilder.php';

class Tests_Extensions_MockObject_Stub_ReturnMappingTest
extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException PHPUnit_Framework_AssertionFailedError
     */
    public function testInvoke_noMatch() {
        $invocation = $this->getMock('Foo');

        $returnMapBuilder =
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder();
        $returnMapBuilder->addEntry()
            ->with(array('hello'))
            ->will($this->returnValue('hello'));
        $invocation
            ->expects($this->any())
            ->method('bar')
            ->with($this->anything())
            ->will($returnMapBuilder->build());

        $invocation->bar('baz');
    }

    public function testInvoke_matchOutOfOne() {
        $invocation = $this->getMock('Foo');

        $returnMapBuilder =
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder();
        $returnMapBuilder->addEntry()
            ->with(array('hello'))
            ->will($this->returnValue('hello'));
        $invocation
            ->expects($this->any())
            ->method('bar')
            ->with($this->anything())
            ->will($returnMapBuilder->build());

        $this->assertEquals('hello', $invocation->bar('hello'));
    }

    
    public function testInvoke_matchOutOfMany() {
        $invocation = $this->getMock('Foo');

        $returnMapBuilder =
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder();
        $returnMapBuilder->addEntry()
            ->with(array('hello'))
            ->will($this->returnValue('hello'));
        $returnMapBuilder->addEntry()
            ->with(array('baz'))
            ->will($this->returnValue('baz'));

        $invocation
            ->expects($this->any())
            ->method('bar')
            ->with($this->anything())
            ->will($returnMapBuilder->build());

        $this->assertEquals('baz', $invocation->bar('baz'));
    }

    public function testInvoke_constraintLessThan() {
        $invocation = $this->getMock('Foo');

        $returnMapBuilder =
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder();
        $returnMapBuilder->addEntry()
            ->with(array($this->lessThan(5)))
            ->will($this->returnValue('less'));
        $returnMapBuilder->addEntry()
            ->with(array($this->greaterThan(5)))
            ->will($this->returnValue('greater'));
        $returnMapBuilder->addEntry()
            ->with(array(5))
            ->will($this->returnValue('equal'));

        $invocation
            ->expects($this->any())
            ->method('bar')
            ->with($this->anything())
            ->will($returnMapBuilder->build());

        $this->assertEquals('less', $invocation->bar(3));
    }

    public function testInvoke_constraintGreaterThan() {
        $invocation = $this->getMock('Foo');

        $returnMapBuilder =
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder();
        $returnMapBuilder->addEntry()
            ->with(array($this->lessThan(5)))
            ->will($this->returnValue('less'));
        $returnMapBuilder->addEntry()
            ->with(array($this->greaterThan(5)))
            ->will($this->returnValue('greater'));
        $returnMapBuilder->addEntry()
            ->with(array(5))
            ->will($this->returnValue('equal'));

        $invocation
            ->expects($this->any())
            ->method('bar')
            ->with($this->anything())
            ->will($returnMapBuilder->build());

        $this->assertEquals('greater', $invocation->bar(7));
    }

    public function testInvoke_constraintEqual() {
        $invocation = $this->getMock('Foo');

        $returnMapBuilder =
            new PHPUnit_Extensions_MockObject_Stub_ReturnMapping_Builder();
        $returnMapBuilder->addEntry()
            ->with(array($this->lessThan(5)))
            ->will($this->returnValue('less'));
        $returnMapBuilder->addEntry()
            ->with(array($this->greaterThan(5)))
            ->will($this->returnValue('greater'));
        $returnMapBuilder->addEntry()
            ->with(array(5))
            ->will($this->returnValue('equal'));

        $invocation
            ->expects($this->any())
            ->method('bar')
            ->with($this->anything())
            ->will($returnMapBuilder->build());

        $this->assertEquals('equal', $invocation->bar(5));
    }
}

class Foo {

  public function bar($baz=NULL) {
    return $baz;
  }
}

