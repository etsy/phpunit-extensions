<?php

class PHPUnit_Extensions_Helper_AccessibleObject {

    const REGEX_ACCESSIBLE = '/@accessibleForTesting/';
    
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
        return $reflected->isPublic()
            || preg_match(
                   self::REGEX_ACCESSIBLE,
                   $reflected->getDocComment(),
                   $matches
               );
    }
}