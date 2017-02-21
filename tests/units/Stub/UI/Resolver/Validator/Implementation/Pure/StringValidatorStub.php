<?php
declare(strict_types = 1);

namespace Tests\Units\Imedia\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure\StringValidatorTrait;
use Tests\Units\Imedia\Ammit\Stub\UI\Resolver\Validator\Implementation\AbstractValidatorStub;

/**
 * Allow unit testing Trait
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class StringValidatorStub extends AbstractValidatorStub
{
    use StringValidatorTrait;
}
