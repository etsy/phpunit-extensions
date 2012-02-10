<?php

require_once 'PHPUnit/Extensions/Helper/AccessibleObject.php';

class Tests_Extensions_Helper_AccessibleObjectTest
extends PHPUnit_Framework_TestCase {

    private $accessible_object;
    
    protected function setUp() {
        parent::setUp();
        $this->accessibleObject = new PHPUnit_Extensions_Helper_AccessibleObject(
            new Tests_Extensions_Helper_AccessibleObject_Object()
        );
    }
    
    /**
     * @expectedException ReflectionException
     */
    public function testPrivateMethod_inaccessible() {
        $this->accessibleObject->privateMethod();
    }
    
    public function testPrivateMethod_accessible() {
        $this->assertEquals(
            'accessible private called',
            $this->accessibleObject->accessiblePrivateMethod()
        );
    }
    
    /**
     * @expectedException ReflectionException
     */
    public function testProtectedMethod_inaccessible() {
        $this->accessibleObject->protectedMethod();
    }
    
    public function testProtectedMethod_accessible() {
        $this->assertEquals(
            'accessible protected called',
            $this->accessibleObject->accessibleProtectedMethod()
        );
    }
    
    public function testPublicMethod() {
        $this->assertEquals(
            'public called',
            $this->accessibleObject->publicMethod()
        );
    }
    
    public function testPublicVar_setGet() {
        $this->accessibleObject->public_var = 3;
        $this->assertEquals(3, $this->accessibleObject->public_var);
    }
    
    public function testPublicVar_issetNo() {
        $this->assertFalse(isset($this->accessibleObject->public_var));
    }
    
    public function testPublicVar_issetYes() {
        $this->accessibleObject->public_var = 3;
        $this->assertTrue(isset($this->accessibleObject->public_var));
    }
    
    public function testPublicVar_unset() {
        $this->accessibleObject->public_var = 3;
        unset($this->accessibleObject->public_var);
        $this->assertFalse(isset($this->accessibleObject->public_var));
    }
}

class Tests_Extensions_Helper_AccessibleObject_Object {

    public $public_var;
    
    /** @accessibleForTesting cannot have more on line */
    private function privateMethod() {
         return 'private called';
    }
    
    /** @accessibleForTesting */
    private function accessiblePrivateMethod() {
         return 'accessible private called';
    }
    
    protected function protectedMethod() {
        return 'protected called';
    }
    
    /**
     * @accessibleForTesting
     */
    protected function accessibleProtectedMethod() {
         return 'accessible protected called';
    }
    
    public function publicMethod() {
        return 'public called';
    }
}