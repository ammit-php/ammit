<?php
declare(strict_types = 1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\Pragmatic;

use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic\InArrayValidatorTrait;
use Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\AbstractValidatorStub;

/**
 * Allow unit testing Trait
 */
class InArrayValidatorStub extends AbstractValidatorStub
{
    use InArrayValidatorTrait;
}
