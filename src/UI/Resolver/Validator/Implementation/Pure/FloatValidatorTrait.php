<?php

namespace Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
trait FloatValidatorTrait
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value Float ?
     *
     * @return float Value casted into float or -1
     */
    public function mustBeFloat($value, string $propertyPath = null, UIValidatorInterface $parentValidator = null, string $exceptionMessage = null): float
    {
        if (is_numeric($value)) {
            $value = (float) $value;
        }

        $this->validationEngine->validateFieldValue(
            $parentValidator ?: $this,
            function() use ($value, $propertyPath, $exceptionMessage) {
                if (is_float($value)) {
                    return;
                }

                if (null === $exceptionMessage) {
                    $exceptionMessage = sprintf(
                        'Value "%s" is not a float.',
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

        if (!is_float($value)) {
            return -1;
        }

        return $value;
    }
}
