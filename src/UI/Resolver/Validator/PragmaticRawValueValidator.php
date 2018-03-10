<?php
declare(strict_types = 1);

namespace AmmitPhp\Ammit\UI\Resolver\Validator;

use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic\StringBetweenLengthValidatorTrait;
use AmmitPhp\Ammit\Domain\MailMxValidation;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic\InArrayValidatorTrait;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic\UuidValidatorTrait;

/**
 * @internal Contains Domain Validation assertions (but class won't be removed in next version)
 *   Domain Validation should be done in Domain
 *   Should be used for prototyping project knowing you are accumulating technical debt
 */
class PragmaticRawValueValidator extends RawValueValidator
{
    use UuidValidatorTrait;
    use InArrayValidatorTrait;
    use StringBetweenLengthValidatorTrait;

    /**
     * Domain should be responsible for string emptiness
     * Exceptions are caught in order to be processed later
     * @param mixed $value String not empty ?
     *
     * @return mixed Untouched value
     */
    public function mustBeStringNotEmpty($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                if (!empty($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is empty.',
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

        return $value;
    }

    /**
     * Domain should be responsible for email format
     * Exceptions are caught in order to be processed later
     * @param mixed $value Email ?
     *
     * @return mixed Untouched value
     */
    public function mustBeEmailAddress($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $mailMxValidation = new MailMxValidation();
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage, $mailMxValidation) {
                if ($mailMxValidation->isEmailFormatValid($value) && $mailMxValidation->isEmailHostValid($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Mail "%s" is not valid.',
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

        return $value;
    }

    /**
     * Domain should be responsible for regex validation
     * Exceptions are caught in order to be processed later
     * @param mixed  $value   Valid against Regex ?
     * @param string $pattern Regex
     *
     * @return mixed Untouched value
     */
    public function mustBeValidAgainstRegex($value, string $pattern, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $pattern, $propertyPath, $exceptionMessage) {
                if (preg_match($pattern, $value)) {
                    return;
                }

                throw new InvalidArgumentException(
                    $exceptionMessage ?: sprintf('Value "%s" not valid against regex "%s".', $value, $pattern),
                    0,
                    $propertyPath,
                    $value
                );
            }
        );

        return $value;
    }
}
