<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pragmatic;

use Imedia\Ammit\Domain\StringValidation;
use Imedia\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait StringBetweenLengthValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Domain should be responsible for id format
     * Exceptions are caught in order to be processed later
     * @param mixed $value String ?
     *
     * @return mixed Untouched value
     */
    public function mustHaveLengthBetween($value, int $min, int $max, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $min, $max, $propertyPath, $exceptionMessage) {
                $stringValidation = new StringValidation();
                if ($stringValidation->isStringBetweenValid($value, $min, $max)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" must have between %d and %d chars.',
                        $value,
                        $min,
                        $max
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
