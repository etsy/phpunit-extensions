<?php
namespace PHPUnit\Extensions\MockObject\Stub\ReturnMapping;

use PHPUnit_Framework_MockObject_Matcher_Parameters;
use PHPUnit_Framework_MockObject_Invocation;
use PHPUnit_Framework_MockObject_Stub;
use PHPUnit\Framework\ExpectationFailedException;

class Entry {

  private $parameters;
  private $return;

  public function __construct(
      PHPUnit_Framework_MockObject_Matcher_Parameters $parameters, $return) {

      $this->parameters = $parameters;
      $this->return = $return;
  }

  public function matches(PHPUnit_Framework_MockObject_Invocation $invocation) {
      try {
          $this->parameters->matches($invocation);
          return true;
      } catch (ExpectationFailedException $ignore) {
          return false;
      }
  }

  public function invoke(PHPUnit_Framework_MockObject_Invocation $invocation) {
      if ($this->return instanceof PHPUnit_Framework_MockObject_Stub) {
          return $this->return->invoke($invocation);
      }
      return $this->return;
  }
}

