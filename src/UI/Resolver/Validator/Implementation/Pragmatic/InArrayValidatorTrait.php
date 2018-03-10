<?php

namespace AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic;

use AmmitPhp\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use AmmitPhp\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait InArrayValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Array ?
     *
     * @return mixed
     */
    public function mustBeInArray($value, array $availableValues, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $availableValues, $propertyPath, $exceptionMessage) {
                if (in_array($value, $availableValues, true)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not valid. Available values are "%s".',
                        $value,
                        implode('", "', $availableValues)
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

        return $value;
    }
}
