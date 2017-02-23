<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pragmatic;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
trait UuidValidatorTrait
{
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
                Assertion::uuid(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return (string) $value;
    }
}
