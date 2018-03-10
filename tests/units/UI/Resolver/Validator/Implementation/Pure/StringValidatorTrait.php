<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Tests\Units\Imedia\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure\StringValidatorStub as SUT;

class StringValidatorTrait extends atoum
{
    /**
     * @dataProvider goodDataProvider
     */
    public function test_it_gets_value_even_if_valid($value, $expected)
    {
        // Given
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeString(
            $value,
            'accept',
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
                'value' => 'good',
                'expected' => 'good'
            ],
        ];
    }

    /**
     * @dataProvider badDataProvider
     */
    public function test_it_gets_value_even_if_invalid($value, $expected)
    {
        // Given
        $propertyPath = 'latitude';
        $errorMessage = 'Custom Exception message';

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
        $actual = $sut->mustBeString(
            $value,
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
                'value' => true,
                'expected' => ''
            ],
            [
                'value' => null,
                'expected' => ''
            ],
            [
                'value' => array(),
                'expected' => ''
            ],
            [
                'value' => 3.14,
                'expected' => ''
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
