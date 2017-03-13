<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
trait BooleanValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Boolean ?
     *
     * @return boolean Value casted into boolean or false
     */
    public function mustBeBoolean($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): bool
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                Assertion::inArray(
                    $value,
                    [true, false, 1, 0, '1', '0', 'true', 'false'],
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        // Otherwise "false" would return true
        if (in_array($value, [true, 'true', '1', 1], true)) {
            return true;
        }

        return false;
    }
}
