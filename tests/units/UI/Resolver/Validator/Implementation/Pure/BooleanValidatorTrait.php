<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Tests\Units\Imedia\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure\BooleanValidatorStub as SUT;

class BooleanValidatorTrait extends atoum
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
        $actual = $sut->mustBeBoolean(
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
                'value' => true,
                'expected' => true
            ],
            [
                'value' => false,
                'expected' => false
            ],
            [
                'value' => 'true',
                'expected' => true
            ],
            [
                'value' => 'false',
                'expected' => false
            ],
            [
                'value' => 1,
                'expected' => true
            ],
            [
                'value' => 0,
                'expected' => false
            ],
        ];
    }

    public function test_it_gets_value_even_if_invalid()
    {
        // Given
        $value = 'bad';
        $propertyPath = 'isOk';
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
        $actual = $sut->mustBeBoolean(
            $value,
            $propertyPath,
            null,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo(false);

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
