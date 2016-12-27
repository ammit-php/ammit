<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Exception;

use mageekguy\atoum;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class CommandMappingException extends atoum
{
    public function test_it_can_be_normalized_with_property_path()
    {
        // Given
        $message = 'Custom message';
        $propertyPath = 'firstName';
        $source = 'pointer';

        $expected = [
            'status' => 406,
            'source' => [
                'pointer' => "/data/attributes/$propertyPath"
            ],
            'title' => 'Invalid Attribute',
            'detail' => $message,
        ];


        // When
        $sut = new SUT($message, $propertyPath, $source);

        // Then
        $this
            ->phpString($sut->getPropertyPath())
                ->isEqualTo($propertyPath)
            ->phpString($sut->getMessage())
                ->isEqualTo($message)
            ->array($sut->normalize())
                ->isEqualTo($expected)
        ;
    }

    public function test_it_can_be_normalized_without_property_path()
    {
        // Given
        $message = 'Custom message';
        $propertyPath = null;
        $source = 'pointer';

        $expected = [
            'status' => 406,
            'source' => [
                'pointer' => "/data/attributes/"
            ],
            'title' => 'Invalid Attribute',
            'detail' => $message,
        ];


        // When
        $sut = new SUT($message, $propertyPath, $source);

        // Then
        $this
            ->phpString($sut->getPropertyPath())
                ->isEqualTo('')
            ->phpString($sut->getMessage())
                ->isEqualTo($message)
            ->array($sut->normalize())
                ->isEqualTo($expected)
        ;
    }
}
