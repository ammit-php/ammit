<?php
declare(strict_types = 1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation;

use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use AmmitPhp\Ammit\UI\Resolver\Validator\UIValidatorInterface;

abstract class AbstractValidatorStub implements UIValidatorInterface
{
    /** @var UIValidationEngine */
    protected $validationEngine;

    public function __construct(UIValidationEngine $validationEngine)
    {
        $this->validationEngine = $validationEngine;
    }

    /**
     * @inheritdoc
     */
    public function createUIValidationException(string $message, string $propertyPath = null): UIValidationException
    {
        return UIValidationException::fromRaw($message, $propertyPath);
    }
}
