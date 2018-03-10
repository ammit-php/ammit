<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\BooleanValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\DateValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\FloatValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\IntegerValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\StringValidatorTrait;
use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\ArrayValidatorTrait;

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
