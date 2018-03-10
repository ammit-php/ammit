<?php
declare(strict_types = 1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure;

use AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure\DateValidatorTrait;
use Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\AbstractValidatorStub;

/**
 * Allow unit testing Trait
 */
class DateValidatorStub extends AbstractValidatorStub
{
    use DateValidatorTrait;
}
