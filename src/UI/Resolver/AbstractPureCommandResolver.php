<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Validator\RequestAttributeValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\RawValueValidator;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\Validator\RequestQueryStringValueValidator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Helper easing Command Resolver (mapping + UI Validation) implementation
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
abstract class AbstractPureCommandResolver
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    /** @var RawValueValidator */
    protected $rawValueValidator;

    /** @var RequestAttributeValueValidator */
    protected $attributeValueValidator;

    /** @var RequestQueryStringValueValidator */
    protected $queryStringValueValidator;

    public function __construct(UIValidationEngine $validationEngine = null, RawValueValidator $rawValueValidator = null, RequestAttributeValueValidator $attributeValueValidator = null, RequestQueryStringValueValidator $queryStringValueValidator = null)
    {
        if (null === $validationEngine) {
            $validationEngine = UIValidationEngine::initialize();
        }

        if (null === $rawValueValidator) {
            $rawValueValidator = new RawValueValidator($validationEngine);
        }

        if (null === $attributeValueValidator) {
            $attributeValueValidator = new RequestAttributeValueValidator(
                $rawValueValidator
            );
        }

        if (null === $queryStringValueValidator) {
            $queryStringValueValidator = new RequestQueryStringValueValidator(
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
