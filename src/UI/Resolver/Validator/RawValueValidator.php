<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Imedia\Ammit\Domain\DateValidation;
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
            function() use ($value, $propertyPath, $exceptionMessage) {
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
            function() use ($value, $propertyPath, $exceptionMessage) {
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
            function() use ($value, $propertyPath, $exceptionMessage) {
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
     * Exceptions are caught in order to be processed later
     * @param mixed $value Float ?
     *
     * @return mixed Untouched value
     */
    public function mustBeFloat($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        if (is_numeric($value)) {
            $value = (float) $value;
        }

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                Assertion::float(
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
     * @param mixed $value Integer ?
     *
     * @return mixed Untouched value
     */
    public function mustBeInteger($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        if (is_numeric($value)) {
            $value = (int) $value;
        }

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                Assertion::integer(
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
     * @param mixed $value Date Y-m-d ?
     *
     * @return mixed Untouched value
     */
    public function mustBeDate($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                $dateValidation = new DateValidation();
                if (! $dateValidation->isDateValid($value)) {
                    throw new InvalidArgumentException(
                        $exceptionMessage ?: sprintf('Date "%s" format invalid, must be Y-m-d.', $value),
                        0,
                        $propertyPath,
                        $value
                    );
                }
            }
        );

        return $value;
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Date Y-m-d\TH:i:sP (RFC3339). Ex: 2016-06-01T00:00:00+00:00 ?
     *
     * @return mixed Untouched value
     */
    public function mustBeDateTime($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                $dateValidation = new DateValidation();
                if (! $dateValidation->isDateTimeValid($value)) {
                    throw new InvalidArgumentException(
                        $exceptionMessage ?: sprintf('Datetime "%s" format invalid, must be Y-m-d\TH:i:sP (RFC3339). Ex: 2016-06-01T00:00:00+00:00.', $value),
                        0,
                        $propertyPath,
                        $value
                    );
                }
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
