<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use Imedia\Ammit\Domain\BooleanValidation;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

trait BooleanValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Boolean ?
     *
     * @return boolean|null Value casted into boolean or null
     */
    public function mustBeBooleanOrEmpty($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        if ($value === '') {
            return null;
        }

        return $this->mustBeBoolean($value, $propertyPath, $parentValidator, $exceptionMessage);
    }

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
                $booleanValidation = new BooleanValidation();
                if ($booleanValidation->isBooleanValid($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not a boolean.',
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

        // Otherwise "false" would return true
        if (in_array($value, [true, 'true', '1', 1], true)) {
            return true;
        }

        return false;
    }
}
