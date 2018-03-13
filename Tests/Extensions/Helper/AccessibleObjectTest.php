<?php

namespace PHPUnit\Extensions\Helper;

use PHPUnit\Framework\TestCase;

use ReflectionException;

class AccessibleObjectTest extends TestCase {

    private $accessible_object;
    
    protected function setUp() {
        parent::setUp();
        $this->accessibleObject = new AccessibleObject(
            new AccessibleObject_Object()
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

    public function testProtectedMultipleCommentMethod_accessible() {
        $this->assertEquals(
            'accessible protected multi comment called',
            $this->accessibleObject->accessibleMultiCommentMethod()
        );
    }
}

class AccessibleObject_Object {

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

    /**
     * This is a test function
     *
     * @accessibleForTesting
     * @return string
     */
    protected function accessibleMultiCommentMethod() {
        return 'accessible protected multi comment called';
    }
}
