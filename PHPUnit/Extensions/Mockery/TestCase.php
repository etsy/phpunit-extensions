<?php

require_once 'Mockery/Loader.php';
require_once 'Hamcrest/Hamcrest.php';

$loader = new \Mockery\Loader();
$loader->register();

use \Mockery;

abstract class PHPUnit_Extensions_Mockery_TestCase extends PHPUnit_Framework_TestCase {

    const REGEX_MOCK = '/@mockery\s+([a-zA-Z0-9._:-\\\\x7f-\xff]+)/';
    
    protected function setUp() {
        parent::setUp();
        $class = new ReflectionClass($this);
        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $doc_comment = $property->getDocComment();
            if (preg_match(self::REGEX_MOCK, $doc_comment, $matches)) {
                $annotations = $this->parseAnnotations($doc_comment);
                if (isset($annotations['mockery'])) {
                    $property_name = $property->getName();
                    $this->{$property_name} =
                        $this->getMockery($annotations['mockery'][0]);
                }
            }
        }
    }
    
    protected function verifyMockObjects() {
        $container = Mockery::getContainer();
        if (isset($container)) {
            $reflected_container = new ReflectionClass($container);
            $reflected_mocks = $reflected_container->getProperty('_mocks');
            $reflected_mocks->setAccessible(true);
            $mocks = $reflected_mocks->getValue($container);
            foreach ($mocks as $mock) {
                $reflected_mock = new ReflectionClass($mock);
                $reflected_expectations =
                    $reflected_mock->getProperty('_mockery_expectations');
                $reflected_expectations->setAccessible(true);
                $expectations = $reflected_expectations->getValue($mock);
                foreach ($expectations as $director) {
                    $this->addToAssertionCount(count($director->getExpectations()));
                }
            }
            Mockery::close();
        }
        
        
        parent::verifyMockObjects();
    }
    
    protected function getMockery() {
        $args = func_get_args();
        return call_user_func_array(array('Mockery', 'mock'), $args);
    }
    
    // TODO: Use PHPUnit_Util_Test::parseAnnotations() instead
    private function parseAnnotations($docblock) {
        $annotations = array();
        // Strip away the docblock header and footer to ease parsing of one line annotations
        $docblock = substr($docblock, 3, -2);

        if (preg_match_all('/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m', $docblock, $matches)) {
            $numMatches = count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                $annotations[$matches['name'][$i]][] = $matches['value'][$i];
            }
        }

        return $annotations;
    }
}