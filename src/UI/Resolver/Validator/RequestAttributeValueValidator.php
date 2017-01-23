<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
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
        try {
            $attributes = $request->getParsedBody();

            Assertion::isArray($attributes);
            Assertion::keyExists($attributes, $attributeKey);

            $value = $attributes[$attributeKey];

            return $value;
        } catch (AssertionFailedException $exception) {
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
     * @return mixed Untouched value
     */
    public function mustBeString(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
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
     * @return mixed Untouched value
     */
    public function mustBeBoolean(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
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
     * @return mixed Untouched value
     */
    public function mustBeArray(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
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
     * @return mixed Untouched value
     */
    public function mustBeFloat(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
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
     * @inheritdoc
     */
    public function createUIValidationException(string $message, string $propertyPath = null): UIValidationException
    {
        return UIValidationException::fromAttribute($message, $propertyPath);
    }
}
