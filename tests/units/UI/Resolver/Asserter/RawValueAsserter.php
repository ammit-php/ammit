<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Asserter;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Imedia\Ammit\UI\Resolver\Asserter\RawValueAsserter as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RawValueAsserter extends atoum
{
    /**
     * @dataProvider notStringDataProvider
     */
    public function test_it_gets_value_even_if_not_string_value_detected($value)
    {
        // Given
        $propertyPath = 'firstName';
        $errorMessage = 'Custom Exception message';

        $this->testInvalidValue($errorMessage, $propertyPath, $value, $value);
    }

    public static function createAllScalars(): array
    {
        return [
            'null' => ['value' => null],
            'string' => ['value' => 'azerty'],
            'array' => ['value' => []],
            'int' => ['value' => 1],
            'float' => ['value' => 1.9],
            'bool' => ['value' => true],
        ];
    }

    protected function notStringDataProvider(): array
    {
        $values = $this->createAllScalars();
        unset($values['string']);

        return $values;
    }

    private function testInvalidValue(string $errorMessage, string $propertyPath, $value, $expectedValue)
    {
        $expectedNormalizedException = new UIValidationCollectionException(
            [
                new UIValidationException($errorMessage, $propertyPath)
            ]
        );
        $expectedNormalizedException = $expectedNormalizedException->normalize();

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->valueMustBeString(
            $value,
            $propertyPath,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($expectedValue);

        try {
            $uiValidationEngine->guardAgainstAnyUIValidationException();
        } catch (UIValidationCollectionException $e) {
            $actual = $e->normalize();
            $this->array($actual)
                ->isEqualTo($expectedNormalizedException);

            return;
        }

        $this->throwError();
    }

    private function throwError()
    {
        throw new \mageekguy\atoum\asserter\exception(
            $this->variable(),
            'UIValidationCollectionException not thrown.'
        );
    }
}
