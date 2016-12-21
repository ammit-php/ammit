<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Asserter;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RawValueAsserter
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
    public function valueMustBeString($value, string $propertyPath = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
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
    public function valueMustBeBoolean($value, string $propertyPath = null, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
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
}
