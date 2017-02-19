<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\InvalidArgumentException;
use Imedia\Ammit\Domain\DateValidation;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\BooleanValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\FloatValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\IntegerValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\StringValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\ArrayValidatorTrait;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RawValueValidator implements UIValidatorInterface
{
    use BooleanValidatorTrait;
    use IntegerValidatorTrait;
    use FloatValidatorTrait;
    use StringValidatorTrait;
    use ArrayValidatorTrait;

    /** @var UIValidationEngine */
    protected $validationEngine;

    public function __construct(UIValidationEngine $validationEngine)
    {
        $this->validationEngine = $validationEngine;
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Date Y-m-d ?
     *
     * @return \DateTime|false
     */
    public function mustBeDate($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null)
    {
        $dateValidation = new DateValidation();
        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage, $dateValidation) {
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

        $date = $dateValidation->createDateFromString($value);
        if ($date instanceof \DateTime) {
            return $date;
        }

        return $value;
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

        $date = $dateValidation->createDateTimeFromString($value);

        if ($date instanceof \DateTime) {
            return $date;
        }

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
