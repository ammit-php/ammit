<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait StringValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value String ?
     *
     * @return string|null Casted to string or null
     */
    public function mustBeStringOrEmpty($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        if (empty($value)) {
            return null;
        }

        return $this->mustBeString($value, $propertyPath, $parentValidator, $exceptionMessage);
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value String ?
     *
     * @return string Casted to string
     */
    public function mustBeString($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): string
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                if (is_string($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not a string.',
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

        if (null === $value || !is_string($value)) {
            return '';
        }

        return (string) $value;
    }
}
