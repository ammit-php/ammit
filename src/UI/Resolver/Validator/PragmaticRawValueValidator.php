<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\Assertion;

/**
 * @deprecated Contains Domain Validation assertions (but class won't be removed in next version)
 *   Domain Validation should be done in Domain
 *   Should be used for prototyping project knowing you are accumulating technical debt
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class PragmaticRawValueValidator extends RawValueValidator
{
    /**
     * Domain should be responsible for id format
     * Exceptions are caught in order to be processed later
     * @param mixed $value String ?
     *
     * @return mixed Untouched value
     */
    public function mustBeUuid($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function () use ($value, $propertyPath, $exceptionMessage) {
                Assertion::uuid(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return $value;
    }
}
