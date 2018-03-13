<?php

namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\LogicalNot;

use stdClass;

class EqualsClosureTest extends TestCase {
    
    /**
     * @dataProvider provideMatchingObjects
     */
    public function testConstraintMatches($expected, $actual) {
        $this->assertThat(
            $actual,
            new EqualsClosure(
                $expected,
                function($expected, $actual) {
                    return $expected->id === $actual->id
                        && $expected->first === $actual->first
                        && $expected->last === $actual->last;
                }
            )
        );
    }
    /**
     * @dataProvider provideMismatchingObjects
     */
    public function testContraintMisMatches($expected, $actual) {
        $this->assertThat(
            $actual,
            new LogicalNot(
                new EqualsClosure(
                    $expected,
                    function($expected, $actual) {
                        return $expected->id === $actual->id
                            && $expected->first === $actual->first
                            && $expected->last === $actual->last;
                    }
                )
            )
        );
    }
    public function provideMatchingObjects() {
        $object_a = new stdClass;
        $object_a->id = 1;
        $object_a->first = 'bob';
        $object_a->last = 'johnson';
        $object_a->age = 21;
        $object_b = new stdClass;
        $object_b->id = 1;
        $object_b->first = 'bob';
        $object_b->last = 'johnson';
        $object_b->age = 29;
        return array(
            array($object_a, $object_a),
            array($object_a, $object_b),
        );
    }
    public function provideMismatchingObjects() {
        $object_a = new stdClass;
        $object_a->id = 1;
        $object_a->first = 'bob';
        $object_a->last = 'johnson';
        $object_a->age = 21;
        $object_c = new stdClass;
        $object_c->id = 2;
        $object_c->first = 'bob';
        $object_c->last = 'johnson';
        $object_c->age = 22;
        $object_d = new StdClass;
        $object_d->id = 1;
        $object_d->first = 'alice';
        $object_d->last = 'johnson';
        $object_d->age = 22;
        return array(
            array($object_a, $object_c),
            array($object_a, $object_d),
        );
    }
}
