<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Imedia\Ammit\UI\Resolver\ValueExtractor;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestAttributeValueValidator implements UIValidatorInterface
{
    /** @var RawValueValidator */
    protected $rawValueValidator;

    public function __construct(RawValueValidator $rawValueValidator)
    {
        $this->rawValueValidator = $rawValueValidator;
    }

    /**
     * Validate if request attribute $_POST field can be mapped to a Command
     * Throw CommandMappingException directly if any mapping issue
     * @param ServerRequestInterface $request
     * @param string $attributeKey
     *
     * @return mixed
     * @throws CommandMappingException If any mapping validation failed
     */
    public function extractValueFromRequestAttribute(ServerRequestInterface $request, string $attributeKey)
    {
        $valueExtractor = new ValueExtractor();

        try {
            return $valueExtractor->fromArray(
                $request->getParsedBody(),
                $attributeKey
            );
        } catch (InvalidArgumentException $exception) {
            throw CommandMappingException::fromAttribute(
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
    public function mustBeString(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): string
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeString(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeBoolean(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): bool
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeBoolean(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeArray(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): array
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeArray(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeFloat(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): float
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeFloat(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeInteger(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): int
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeInteger(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeDate(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): \DateTime
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeDate(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * Exceptions are caught in order to be processed later
     *
     * @throws CommandMappingException If any mapping validation failed
     */
    public function mustBeDateTime(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null): \DateTime
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);

        return $this->rawValueValidator->mustBeDateTime(
            $value,
            $attributeKey,
            $this,
            $exceptionMessage
        );
    }

    /**
     * @inheritdoc
     */
    public function createUIValidationException(string $message, string $propertyPath = null): UIValidationException
    {
        return UIValidationException::fromAttribute($message, $propertyPath);
    }
}
