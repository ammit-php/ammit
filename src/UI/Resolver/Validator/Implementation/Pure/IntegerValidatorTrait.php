<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
trait IntegerValidatorTrait
{
    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Integer ?
     *
     * @return int Value casted into int or -1
     */
    public function mustBeInteger($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): int
    {
        if (is_numeric($value)) {
            $value = (int) $value;
        }

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                Assertion::integer(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        if (!is_int($value)) {
            return -1;
        }

        return $value;
    }
}
