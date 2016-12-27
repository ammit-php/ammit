<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Validator\PragmaticRawValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\PragmaticRequestAttributeValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\RequestAttributeValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\RawValueValidator;
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

    /** @var PragmaticRequestAttributeValueValidator */
    private $attributeValueValidator;

    /** @var PragmaticRawValueValidator */
    private $rawValueValidator;

    public function __construct(UIValidationEngine $validationEngine = null, PragmaticRequestAttributeValueValidator $attributeValueValidator = null, PragmaticRawValueValidator $rawValueValidator = null)
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

        $this->validationEngine = $validationEngine;
        $this->rawValueValidator = $rawValueValidator;
        $this->attributeValueValidator = $attributeValueValidator;
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
            $this->attributeValueValidator,
            $this->rawValueValidator,
            $request
        );

        $this->validationEngine->guardAgainstAnyUIValidationException();

        return $values;
    }

    /**
     * @api
     * Resolve implementation
     * @param RequestAttributeValueValidator $attributeValueValidator
     * @param RawValueValidator $rawValueValidator
     * @param ServerRequestInterface $request
     *
     * @return mixed[]
     */
    abstract protected function validateThenMapAttributes(RequestAttributeValueValidator $attributeValueValidator, RawValueValidator $rawValueValidator, ServerRequestInterface $request): array;
}
