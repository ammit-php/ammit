<?php
declare(strict_types = 1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure;

use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\ArrayValidatorTrait;
use Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\AbstractValidatorStub;

/**
 * Allow unit testing Trait
 */
class ArrayValidatorStub extends AbstractValidatorStub
{
    use ArrayValidatorTrait;
}
