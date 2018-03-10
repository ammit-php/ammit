<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pragmatic;

use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\Pragmatic\InArrayValidatorStub as SUT;

class InArrayValidatorTrait extends atoum
{
    /**
     * @dataProvider goodDataProvider
     */
    public function test_it_gets_value_even_if_valid($value, $expected)
    {
        // Given
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);
        $available = ['a', 'b'];

        // When
        $actual = $sut->mustBeInArray(
            $value,
            $available,
            'type',
            null,
            'Custom Exception message'
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($expected);

        $uiValidationEngine->guardAgainstAnyUIValidationException();
    }

    protected function goodDataProvider(): array
    {
        return [
            [
                'value' => 'a',
                'expected' => 'a'
            ],
        ];
    }

    /**
     * @dataProvider badDataProvider
     */
    public function test_it_gets_value_even_if_invalid($value, $expected)
    {
        // Given
        $propertyPath = 'type';
        $errorMessage = 'Custom Exception message';
        $available = ['a', 'b'];

        $expectedNormalizedExceptions = [
            'errors' => [
                [
                'status' => 406,
                'source' => ['parameter' => $propertyPath],
                'title' => 'Invalid Parameter',
                'detail' => $errorMessage,
                ]
            ]
        ];

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeInArray(
            $value,
            $available,
            $propertyPath,
            null,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isIdenticalTo($expected);

        try {
            $uiValidationEngine->guardAgainstAnyUIValidationException();
        } catch (UIValidationCollectionException $e) {
            $actual = $e->normalize();
            $this->array($actual)
                ->isEqualTo($expectedNormalizedExceptions);

            return;
        }

        $this->throwError();
    }

    protected function badDataProvider(): array
    {
        return [
            [
                'value' => 'c',
                'expected' => 'c'
            ],
            [
                'value' => true,
                'expected' => true
            ],
            [
                'value' => null,
                'expected' => null
            ],
            [
                'value' => array(),
                'expected' => array()
            ],
            [
                'value' => 3.14,
                'expected' => 3.14
            ],
            [
                'value' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaazzzz',
                'expected' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaazzzz'
            ],
        ];
    }

    private function throwError()
    {
        throw new \mageekguy\atoum\asserter\exception(
            $this->variable(true),
            'UIValidationCollectionException not thrown.'
        );
    }
}
