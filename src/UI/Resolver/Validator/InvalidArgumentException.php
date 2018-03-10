<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

class InvalidArgumentException extends \InvalidArgumentException
{
    /** @var string|null */
    private $propertyPath;

    /** @var mixed */
    private $value;

    public function __construct($message, int $code = 0, string $propertyPath = null, $value)
    {
        parent::__construct($message, $code);

        $this->propertyPath = $propertyPath;
        $this->value = $value;
    }

    /**
     * User controlled way to define a sub-property causing
     * the failure of a currently asserted objects.
     *
     * Useful to transport information about the nature of the error
     * back to higher layers.
     *
     * @return string|null
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * Get the value that caused the assertion to fail.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
