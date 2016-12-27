<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestAttributeValueValidator
{
    /** @var RawValueValidator */
    protected $rawValueValidator;

    public function __construct(RawValueValidator $rawValueValidator)
    {
        $this->rawValueValidator = $rawValueValidator;
    }

    /**
     * Validate if request field can be mapped to a Command
     * Throw CommandMappingException directly
     *
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
            throw new CommandMappingException(
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
            $exceptionMessage
        );
    }
}
