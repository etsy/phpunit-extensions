<?php
namespace PHPUnit\Extensions\Helper;

use ReflectionClass;

class AccessibleObject {

    const REGEX_ACCESSIBLE = '/@accessibleForTesting\s*$|\n/';
    
    private $object;
    private $reflection_class;
    
    public function __construct(
        $object
    ) {
        $this->object = $object;
        $this->reflection_class = new ReflectionClass($object);
    }
    
    public function __call($name, $arguments) {
        $reflection_method = $this->reflection_class->getMethod($name);
        $reflection_method->setAccessible(
            $this->isAccessible($reflection_method)
        );
        return $reflection_method->invokeArgs($this->object, $arguments);
    }
    
    public function __get($name) {
        return $this->object->{$name};
    }
    
    public function __set($name, $value) {
        return $this->object->{$name} = $value;
    }
    
    public function __isset($name) {
        return isset($this->object->{$name});
    }
    
    public function __unset($name) {
        unset($this->object->{$name});
    }
    
    private function isAccessible($reflected) {
        $docblock = substr($reflected->getDocComment(), 3, -2);
        return $reflected->isPublic()
            || preg_match(
                   self::REGEX_ACCESSIBLE,
                   $docblock,
                   $matches
               );
    }
}