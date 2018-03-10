<?php

namespace AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure;

use AmmitPhp\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use AmmitPhp\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait FloatValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Float ?
     *
     * @return float Value casted into float or -1
     */
    public function mustBeFloat($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): float
    {
        if (is_numeric($value)) {
            $value = (float) $value;
        }

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                if (is_float($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not a float.',
                        $value
                    );
                }

                throw new InvalidArgumentException(
                    $exceptionMessage,
                    0,
                    $propertyPath,
                    $value
                );
            }
        );

        if (!is_float($value)) {
            return -1;
        }

        return $value;
    }
}
