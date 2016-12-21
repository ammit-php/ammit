<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Asserter\PragmaticRawValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\PragmaticRequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RawValueAsserter;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Helper easing Command Resolver (mapping + UI Validation) implementation
 * @deprecated Contains Domain Validation assertions (but class won't be removed in next version)
 *   Prefer using AbstractPureCommandResolver
 *   Domain Validation should be done in Domain
 *   Should be used for prototyping project knowing you are accumulating technical debt
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
abstract class AbstractPragmaticCommandResolver
{
    /** @var UIValidationEngine */
    private $validationEngine;

    /** @var PragmaticRequestAttributeValueAsserter */
    private $attributeValueAsserter;

    /** @var PragmaticRawValueAsserter */
    private $rawValueAsserter;

    public function __construct(UIValidationEngine $validationEngine = null, PragmaticRequestAttributeValueAsserter $attributeValueAsserter = null, PragmaticRawValueAsserter $rawValueAsserter = null)
    {
        if (null === $validationEngine) {
            $validationEngine = UIValidationEngine::initialize();
        }

        if (null === $rawValueAsserter) {
            $rawValueAsserter = new PragmaticRawValueAsserter($validationEngine);
        }

        if (null === $attributeValueAsserter) {
            $attributeValueAsserter = new PragmaticRequestAttributeValueAsserter(
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
