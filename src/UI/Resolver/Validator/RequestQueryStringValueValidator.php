<?php
declare(strict_types = 1);

namespace AmmitPhp\Ammit\UI\Resolver\Validator;

use AmmitPhp\Ammit\UI\Resolver\Exception\CommandMappingException;
use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationException;
use AmmitPhp\Ammit\UI\Resolver\ValueExtractor;
use Psr\Http\Message\ServerRequestInterface;

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
        $valueExtractor = new ValueExtractor();

        try {
            return $valueExtractor->fromArray(
                $request->getQueryParams(),
                $queryStringKey
            );
        } catch (InvalidArgumentException $exception) {
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
     */
    public function mustBeString(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): string
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
     * @return string|null
     */
    public function mustBeStringOrEmpty(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeStringOrEmpty(
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
     */
    public function mustBeBoolean(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): bool
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
     * @return bool|null
     */
    public function mustBeBooleanOrEmpty(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeBooleanOrEmpty(
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
     */
    public function mustBeArray(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): array
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
     */
    public function mustBeFloat(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): float
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
     */
    public function mustBeInteger(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): int
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
     * @return int|null
     */
    public function mustBeIntegerOrEmpty(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeIntegerOrEmpty(
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
     */
    public function mustBeDate(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): \DateTime
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
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeDateTime(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null): \DateTime
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeDateTime(
            $value,
            $queryStringKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @return \DateTime|null
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeDateTimeOrEmpty(ServerRequestInterface $request, string $queryStringKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestQueryString($request, $queryStringKey);

        return $this->rawValueValidator->mustBeDateTimeOrEmpty(
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
