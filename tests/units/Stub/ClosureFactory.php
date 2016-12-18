<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\Stub;

use Assert\InvalidArgumentException;

/**
 * Creating \Closure directly in an Atoum test is a really bad idea
 * @see $this in \Closure
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class ClosureFactory
{
    public static function createInvalidClosure(string $propertyPath, string $message = 'Message', $value = 'empty'): \Closure
    {
        return function () use ($propertyPath, $message, $value) {
            throw new InvalidArgumentException($message, 0, $propertyPath, $value);
        };
    }
}
