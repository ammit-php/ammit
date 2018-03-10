<?php

namespace AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure;

use AmmitPhp\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use AmmitPhp\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait ArrayValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Array ?
     *
     * @return array Empty array if not array
     */
    public function mustBeArray($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): array
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                if (is_array($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not an array.',
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

        if (!is_array($value)) {
            return [];
        }

        return $value;
    }
}
