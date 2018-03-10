<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\UI\Resolver\Validator;

use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use AmmitPhp\Ammit\UI\Resolver\Validator\RawValueValidator as SUT;

class RawValueValidator extends atoum
{
    public static function getSutMethodNames(): array
    {
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        $methods = get_class_methods($sut);

        $methods = self::removeFromArray('__construct', $methods);
        $methods = self::removeFromArray('createUIValidationException', $methods);

        return $methods;
    }

    private static function removeFromArray(string $method, array $methods): array
    {
        if (($key = array_search($method, $methods)) !== false) {
            unset($methods[$key]);
        }

        return $methods;
    }
}
