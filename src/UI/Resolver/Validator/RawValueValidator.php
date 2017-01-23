<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RawValueValidator implements UIValidatorInterface
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    public function __construct(UIValidationEngine $validationEngine)
    {
        $this->validationEngine = $validationEngine;
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value String ?
     *
     * @return mixed Untouched value
     */
    public function mustBeString($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function () use ($value, $propertyPath, $exceptionMessage) {
                Assertion::string(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return $value;
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Boolean ?
     *
     * @return mixed Untouched value
     */
    public function mustBeBoolean($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function () use ($value, $propertyPath, $exceptionMessage) {
                Assertion::inArray(
                    $value,
                    [true, false, 1, 0, '1', '0', 'true', 'false'],
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return $value;
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Array ?
     *
     * @return mixed Untouched value
     */
    public function mustBeArray($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function () use ($value, $propertyPath, $exceptionMessage) {
                Assertion::isArray(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function createUIValidationException(string $message, string $propertyPath = null): UIValidationException
    {
        return UIValidationException::fromRaw($message, $propertyPath);
    }
}
