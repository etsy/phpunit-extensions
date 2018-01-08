<?php
namespace PHPUnit\Extensions\MockObject\Stub\ReturnMapping;

use PHPUnit\Framework\TestCase;

class ReturnMappingTest extends TestCase {

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testInvoke_noMatch() {
        $invocation = $this->createMock('Foo');

        $returnMapBuilder =
            new Builder();
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
        $invocation = $this->createMock('Foo');

        $returnMapBuilder =
            new Builder();
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
        $invocation = $this->createMock('Foo');

        $returnMapBuilder =
            new Builder();
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
        $invocation = $this->createMock('Foo');

        $returnMapBuilder =
            new Builder();
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
        $invocation = $this->createMock('Foo');

        $returnMapBuilder =
            new Builder();
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
        $invocation = $this->createMock('Foo');

        $returnMapBuilder =
            new Builder();
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

