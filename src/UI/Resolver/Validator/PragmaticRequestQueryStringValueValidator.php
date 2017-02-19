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
class PragmaticRequestQueryStringValueValidator extends RequestQueryStringValueValidator
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
    public function mustBeUuid(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeUuid(
            $value,
            $queryStringKey,
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
    public function mustBeStringNotEmpty(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeStringNotEmpty(
            $value,
            $queryStringKey,
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
    public function mustHaveLengthBetween(ServerRequestInterface $request, string $queryStringKey, int $min, int $max, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustHaveLengthBetween(
            $value,
            $queryStringKey,
            $min,
            $max,
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
    public function mustBeEmailAddress(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeEmailAddress(
            $value,
            $queryStringKey,
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
    public function mustBeValidAgainstRegex(ServerRequestInterface $request, string $pattern, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeValidAgainstRegex(
            $value,
            $pattern,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }
}
