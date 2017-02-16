<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal Contains Domain Validation assertions (but class won't be removed in next version)
 *   Domain Validation should be done in Domain
 *   Should be used for prototyping project knowing you are accumulating technical debt
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class PragmaticRequestAttributeValueValidator extends RequestAttributeValueValidator
{
    /** @var RawValueValidator */
    protected $rawValueValidator;

    public function __construct(PragmaticRawValueValidator $rawValueValidator)
    {
        $this->rawValueValidator = $rawValueValidator;
    }

    /**
     * Domain should be responsible for id format
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeUuid(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeUuid(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Domain should be responsible for string emptiness
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeStringNotEmpty(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeStringNotEmpty(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Domain should be responsible for id format
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustHaveLengthBetween(ServerRequestInterface $request, string $attributeKey, int $min, int $max, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustHaveLengthBetween(
            $value,
            $min,
            $max,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Domain should be responsible for email format
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeEmailAddress(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeEmailAddress(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Domain should be responsible for regex validation
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeValidAgainstRegex(ServerRequestInterface $request, string $pattern, string $attributeKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeValidAgainstRegex(
            $value,
            $pattern,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

}
