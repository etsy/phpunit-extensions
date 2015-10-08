<?php

use SebastianBergmann\Exporter\Exporter;

/**
 * Constraint that asserts that the array it is evaluated for has a given key
 * and value combination.
 */
class PHPUnit_Extensions_Constraint_ArrayHasKeyValuePair extends PHPUnit_Framework_Constraint
{
    /**
     * @var integer|string
     */
    protected $key;
    /**
     * @var mixed
     */
    protected $value;

    protected $exporter;
    /**
     * @param integer|string $key
     * @param mixed $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
	$this->exporter = new Exporter;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns TRUE if the
     * constraint is met, FALSE otherwise.
     *
     * @param array|ArrayAccess $other Value or object to evaluate.
     * @return bool
     */
    protected function matches($other)
    {
        if (!(is_array($other) || $other instanceof ArrayAccess)) {
            return false;
        }
        return (array_key_exists($this->key, $other) && ($other[$this->key] === $this->value));
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return 'has the key ' . $this->exporter->export($this->key) .
                ' with the value ' . $this->exporter->export($this->value);
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param  mixed $other Evaluated value or object.
     * @return string
     */
    protected function failureDescription($other)
    {
        return 'an array ' . $this->toString();
    }
}
