<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestQueryStringValueValidator implements UIValidatorInterface
{
    /** @var RawValueValidator */
    protected $rawValueValidator;

    public function __construct(RawValueValidator $rawValueValidator)
    {
        $this->rawValueValidator = $rawValueValidator;
    }

    /**
     * Validate if request query string $_GET field can be mapped to a Command
     * Throw CommandMappingException directly if any mapping issue
     * @param ServerRequestInterface $request
     * @param string $queryStringKey
     *
     * @return mixed
     * @throws CommandMappingException If any mapping validation failed
     */
    public function extractValueFromRequestQueryString(ServerRequestInterface $request, string $queryStringKey)
    {
        try {
            $queryParams = $request->getQueryParams();

            Assertion::isArray($queryParams);
            Assertion::keyExists($queryParams, $queryStringKey);

            $value = $queryParams[$queryStringKey];

            return $value;
        } catch (AssertionFailedException $exception) {
            throw CommandMappingException::fromParameter(
                $exception->getMessage(),
                $exception->getPropertyPath()
            );
        }
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeString(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeString(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeBoolean(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeBoolean(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeArray(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeArray(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeFloat(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeFloat(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeInteger(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeInteger(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     * @return mixed Untouched value
     */
    public function mustBeDate(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeDate(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * @inheritdoc
     */
    public function createUIValidationException(string $message, string $propertyPath = null): UIValidationException
    {
        return UIValidationException::fromParameter($message, $propertyPath);
    }
}
