<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Asserter\RequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RawValueAsserter;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Helper easing Command Resolver (mapping + UI Validation) implementation
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
abstract class AbstractCommandResolver
{
    /** @var UIValidationEngine */
    private $validationEngine;

    /** @var RequestAttributeValueAsserter */
    private $attributeValueAsserter;

    /** @var RawValueAsserter */
    private $rawValueAsserter;

    public function __construct(UIValidationEngine $validationEngine = null, RequestAttributeValueAsserter $attributeValueAsserter = null, RawValueAsserter $rawValueAsserter = null)
    {
        if (null === $validationEngine) {
            $validationEngine = UIValidationEngine::initialize();
        }

        if (null === $rawValueAsserter) {
            $rawValueAsserter = new RawValueAsserter($validationEngine);
        }

        if (null === $attributeValueAsserter) {
            $attributeValueAsserter = new RequestAttributeValueAsserter(
                $rawValueAsserter
            );
        }

        $this->validationEngine = $validationEngine;
        $this->rawValueAsserter = $rawValueAsserter;
        $this->attributeValueAsserter = $attributeValueAsserter;
    }

    /**
     * @api
     * Create a Command from a Request
     * Perform the UI Validation (simple validation)
     * Complex Validation will be done in the Domain
     * @param ServerRequestInterface $request PSR7 Request
     *
     * @return object Immutable Command (DTO)
     * @throws UIValidationCollectionException If any Validation fail
     */
    abstract public function resolve(ServerRequestInterface $request);

    /**
     * @api
     * Map a Command attributes from a Request into an array
     * Perform the UI Validation (simple validation)
     *
     * @return mixed[] Attributes used to create the Command
     * @throws CommandMappingException If any mapping validation failed
     * @throws UIValidationCollectionException If any UI validation failed
     */
    protected function resolveRequestAsArray(ServerRequestInterface $request): array
    {
        $values = $this->validateThenMapAttributes(
            $this->attributeValueAsserter,
            $this->rawValueAsserter,
            $request
        );

        $this->validationEngine->guardAgainstAnyUIValidationException();

        return $values;
    }

    /**
     * @api
     * Resolve implementation
     * @param RequestAttributeValueAsserter $attributeValueAsserter
     * @param RawValueAsserter $rawValueAsserter
     * @param ServerRequestInterface $request
     *
     * @return mixed[]
     */
    abstract protected function validateThenMapAttributes(RequestAttributeValueAsserter $attributeValueAsserter, RawValueAsserter $rawValueAsserter, ServerRequestInterface $request): array;
}
