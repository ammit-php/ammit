<?php
declare(strict_types = 1);

namespace AmmitPhp\Ammit\UI\Resolver\Validator;

use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\BooleanValidatorTrait;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\DateValidatorTrait;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\FloatValidatorTrait;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\IntegerValidatorTrait;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\StringValidatorTrait;
use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\ArrayValidatorTrait;

class RawValueValidator implements UIValidatorInterface
{
    use BooleanValidatorTrait;
    use IntegerValidatorTrait;
    use FloatValidatorTrait;
    use StringValidatorTrait;
    use ArrayValidatorTrait;
    use DateValidatorTrait;

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
