<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Asserter;

use Assert\Assertion;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestQueryValueAsserter
{
    const PROPERTY_PATH_QUERY_STRING = 'queryString';

    /** @var UIValidationEngine */
    private $validationEngine;

    public function __construct(UIValidationEngine $validationEngine)
    {
        $this->validationEngine = $validationEngine;
    }

    /**
     * Exceptions are caught in order to be processed later
     * @param mixed $value
     *
     * @return mixed Untouched value
     */
    public function valueMustNotBeEmpty($value, string $propertyPath = self::PROPERTY_PATH_QUERY_STRING, string $exceptionMessage = null)
    {
        $this->validationEngine->validateFieldValue(
            function () use ($value, $propertyPath, $exceptionMessage) {
                Assertion::notEmpty(
                    $value,
                    $exceptionMessage,
                    $propertyPath
                );
            }
        );

        return $value;
    }
}
