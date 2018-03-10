<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Validator\PragmaticRawValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\PragmaticRequestAttributeValueValidator;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\Validator\PragmaticRequestQueryStringValueValidator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Helper easing Command Resolver (mapping + UI Validation) implementation
 * @internal Contains Domain Validation assertions (but class won't be removed in next version)
 *   Prefer using AbstractPureCommandResolver
 *   Domain Validation should be done in Domain
 *   Should be used for prototyping project knowing you are accumulating technical debt
 */
abstract class AbstractPragmaticCommandResolver
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /** @var PragmaticRawValueValidator */
    protected $rawValueValidator;

    /** @var PragmaticRequestAttributeValueValidator */
    protected $attributeValueValidator;

    /** @var PragmaticRequestQueryStringValueValidator */
    protected $queryStringValueValidator;

    public function __construct(UIValidationEngine $validationEngine = null, PragmaticRawValueValidator $rawValueValidator = null, PragmaticRequestAttributeValueValidator $attributeValueValidator = null, PragmaticRequestQueryStringValueValidator $queryStringValueValidator = null)
    {
        if (null === $validationEngine) {
            $validationEngine = UIValidationEngine::initialize();
        }

        if (null === $rawValueValidator) {
            $rawValueValidator = new PragmaticRawValueValidator($validationEngine);
        }

        if (null === $attributeValueValidator) {
            $attributeValueValidator = new PragmaticRequestAttributeValueValidator(
                $rawValueValidator
            );
        }

        if (null === $queryStringValueValidator) {
            $queryStringValueValidator = new PragmaticRequestQueryStringValueValidator(
                $rawValueValidator
            );
        }

        $this->validationEngine = $validationEngine;
        $this->rawValueValidator = $rawValueValidator;
        $this->attributeValueValidator = $attributeValueValidator;
        $this->queryStringValueValidator = $queryStringValueValidator;
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
            $request
        );

        $this->validationEngine->guardAgainstAnyUIValidationException();

        return $values;
    }

    /**
     * @api
     * Resolve implementation
     * @param ServerRequestInterface $request PSR-7 Request
     *
     * @return mixed[]
     */
    abstract protected function validateThenMapAttributes(ServerRequestInterface $request): array;
}
