<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Asserter;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestAttributeValueAsserter
{
    /** @var RequestQueryValueAsserter */
    private $requestQueryValueAsserter;

    public function __construct(RequestQueryValueAsserter $requestQueryValueAsserter)
    {
        $this->requestQueryValueAsserter = $requestQueryValueAsserter;
    }

    /**
     * Validate if request field can be mapped to a Command
     * Throw CommandMappingException directly
     *
     * @param ServerRequestInterface $request
     * @param string $attributeKey
     *
     * @return mixed
     * @throws CommandMappingException
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
     * @return mixed
     * @throws CommandMappingException
     */
    public function attributeMustNotBeEmpty(ServerRequestInterface $request, string $attributeKey, string $exceptionMessage = null)
    {
        $value = $this->extractValueFromRequestAttribute($request, $attributeKey);
        $this->requestQueryValueAsserter->valueMustNotBeEmpty(
            $value,
            $attributeKey,
            $exceptionMessage
        );

        return $value;
    }
}
