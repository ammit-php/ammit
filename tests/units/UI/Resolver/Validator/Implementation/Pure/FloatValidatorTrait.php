<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\UI\Resolver\Validator\Implementation\Pure;

use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Tests\Units\AmmitPhp\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure\FloatValidatorStub as SUT;

class FloatValidatorTrait extends atoum
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
        $actual = $sut->mustBeFloat(
            $value,
            'age',
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
            ['value' => 42, 'expected' => 42.0],
            ['value' => '42', 'expected' => 42.0],
            ['value' => '42.0', 'expected' => 42.0],
            ['value' => 42.0, 'expected' => 42.0],
        ];
    }

    public function test_it_gets_value_even_if_invalid()
    {
        // Given
        $value = 'bad';
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
        $actual = $sut->mustBeFloat(
            $value,
            $propertyPath,
            null,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo(-1);

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

    private function throwError()
    {
        throw new \mageekguy\atoum\asserter\exception(
            $this->variable(true),
            'UIValidationCollectionException not thrown.'
        );
    }
}
