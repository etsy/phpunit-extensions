<?php

interface Tests_Extensions_Mockery_TestCase_Foo {

    function foo();
}

interface Tests_Extensions_Mockery_TestCase_Bar {

    function bar();
}

class Tests_Extensions_Mockery_TestCase_Baz {

    private $foo;
    
    function __construct($foo) {
        $this->foo = $foo;
    }
    
    function baz() {
        $this->foo->foo();
    }
}

class Tests_Extensions_Mockery_TestCaseTest
extends PHPUnit_Extensions_Mockery_TestCase {

    /** @mockery Tests_Extensions_Mockery_TestCase_Foo */
    protected $foo;
    
    /** @mockery Tests_Extensions_Mockery_TestCase_Bar */
    protected $bar;
    
    protected $baz;
    
    protected $old;
    
    protected function setUp() {
        parent::setUp();
        $this->baz = $this->getMockery(
            new Tests_Extensions_Mockery_TestCase_Baz($this->foo)
        );
        $this->old = $this->getMockBuilder('Tests_Extensions_Mockery_TestCase_Baz')
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    public function testFoo_notNull() {
        $this->assertNotNull($this->foo);
    }
    
    public function testFoo_canMockFunction() {
        $this->foo->shouldReceive('foo')->andReturn(2)->atLeast(1);
        $this->assertEquals(2, $this->foo->foo());
    }
    
    public function testBar_notNull() {
        $this->assertNotNull($this->bar);
    }
    
    public function testBar_canMockFunction() {
        $this->bar->shouldReceive('bar')->never();
    }
    
    public function testBaz_notNull() {
        $this->assertNotNull($this->baz);
    }
    
    public function testBaz_partialCallsThrough() {
        $this->foo->shouldReceive('foo')->once();
        $this->baz->baz();
    }
    
    public function testBaz_bypassFoo() {
        $this->foo->shouldReceive('foo')->never();
        $this->baz->shouldReceive('baz')->once();
        $this->baz->baz();
    }
    
    public function testOld_notNull() {
        $this->assertNotNull($this->old);
    }
    
    public function testOld_canMockFunction() {
        $this->old
            ->expects($this->atLeastOnce())
            ->method('baz')
            ->will($this->returnSelf());
        $this->assertEquals($this->old, $this->old->baz());
    }
}
