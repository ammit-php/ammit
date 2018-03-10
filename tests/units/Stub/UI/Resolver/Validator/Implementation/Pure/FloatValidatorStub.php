<?php
declare(strict_types = 1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure;

use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\FloatValidatorTrait;
use Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\AbstractValidatorStub;

/**
 * Allow unit testing Trait
 */
class FloatValidatorStub extends AbstractValidatorStub
{
    use FloatValidatorTrait;
}
