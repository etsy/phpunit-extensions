<?php
namespace PHPUnit\Extensions\Constraint;

use PHPUnit\Framework\TestCase;
use PHPUnit\Extensions\Assert\More;

/**
 * @covers ArrayEqualsNoOrder
 */
class ArrayEqualsNoOrderTest extends TestCase {


    public function testToString() {
        $constraint = new ArrayEqualsNoOrder(
            array(1, 2, 3)
        );
        More::assertStringMatchIgnoreWhitespace(
            'has items Array ( 0 => 1 1 => 2 2 => 3 ) and count matches 3',
            $constraint->toString());
    }
}

