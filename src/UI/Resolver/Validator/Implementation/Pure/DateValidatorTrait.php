<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use Imedia\Ammit\Domain\DateValidation;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
trait DateValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Date Y-m-d ?
     *
     * @return \DateTime
     */
    public function mustBeDate($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $dateValidation = new DateValidation();
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage, $dateValidation) {
                if (null === $value || ! $dateValidation->isDateValid($value)) {
                    throw new InvalidArgumentException(
                        $exceptionMessage ?: sprintf('Date "%s" format invalid, must be Y-m-d.', $value),
                        0,
                        $propertyPath,
                        $value
                    );
                }
            }
        );

        if (null === $value) {
            return $this->createDefaultDateTime(); // Invalid
        }

        $date = $dateValidation->createDateFromString($value);
        if ($date instanceof \DateTime) {
            return $date->setTime(0, 0, 0); // Valid
        }

        return $this->createDefaultDateTime(); // Invalid
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value null|DateTime Y-m-d\TH:i:sP (RFC3339). Ex: 2016-06-01T00:00:00+00:00 or null ?
     *
     * @return \DateTime|null
     */
    public function mustBeDateTimeOrEmpty($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        if (empty($value)) {
            return null;
        }

        return $this->mustBeDateTime($value, $propertyPath, $parentValidator, $exceptionMessage);
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Date Y-m-d\TH:i:sP (RFC3339). Ex: 2016-06-01T00:00:00+00:00 ?
     *
     * @return \DateTime|false
     */
    public function mustBeDateTime($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $dateValidation = new DateValidation();

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage, $dateValidation) {
                if (null === $value || ! $dateValidation->isDateTimeValid($value)) {
                    throw new InvalidArgumentException(
                        $exceptionMessage ?: sprintf('Datetime "%s" format invalid, must be Y-m-d\TH:i:sP (RFC3339). Ex: 2016-06-01T00:00:00+00:00.', $value),
                        0,
                        $propertyPath,
                        $value
                    );
                }
            }
        );

        if (null === $value) {
            return $this->createDefaultDateTime(); // Invalid
        }

        $date = $dateValidation->createDateTimeFromString($value);

        if ($date instanceof \DateTime) {
            return $date; // Valid
        }

        return $this->createDefaultDateTime();  // Invalid
    }

    private function createDefaultDateTime(): \DateTime
    {
        $date = new \DateTime();

        return $date->setTime(0, 0, 0);
    }
}
