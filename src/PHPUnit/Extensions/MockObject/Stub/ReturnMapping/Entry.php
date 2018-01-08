<?php
namespace PHPUnit\Extensions\MockObject\Stub\ReturnMapping;

use PHPUnit\Framework\MockObject\Matcher\Parameters;
use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\ExpectationFailedException;

class Entry {

  private $parameters;
  private $return;

  public function __construct(Parameters $parameters, $return) {

      $this->parameters = $parameters;
      $this->return = $return;
  }

  public function matches(Invocation $invocation) {
      try {
          $this->parameters->matches($invocation);
          return true;
      } catch (ExpectationFailedException $ignore) {
          return false;
      }
  }

  public function invoke(Invocation $invocation) {
      if ($this->return instanceof Stub) {
          return $this->return->invoke($invocation);
      }
      return $this->return;
  }
}

