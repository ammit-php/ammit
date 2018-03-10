<?php

namespace AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic;

use AmmitPhp\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use AmmitPhp\Ammit\Domain\UuidValidation;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use AmmitPhp\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait UuidValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Domain should be responsible for id format
     * Exceptions are caught in order to be processed later
     * @param mixed $value UUID ?
     *
     * @return string Casted to string
     */
    public function mustBeUuid($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): string
    {
        if (null === $value || !is_string($value)) {
            $value = '';
        }

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                $uuidValidation = new UuidValidation();
                if ($uuidValidation->isUuidValid($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not a valid UUID.',
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

        return (string) $value;
    }
}
