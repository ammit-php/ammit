<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
trait StringValidatorTrait
{
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
                Assertion::string(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return (string) $value;
    }
}
