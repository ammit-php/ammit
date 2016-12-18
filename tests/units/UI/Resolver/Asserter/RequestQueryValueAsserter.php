<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Asserter;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Imedia\Ammit\UI\Resolver\Asserter\RequestQueryValueAsserter as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestQueryValueAsserter extends atoum
{
    public function test_it_gets_value_even_if_null()
    {
        // Given
        $expected = null;
        $propertyPath = 'firstName';
        $errorMessage = 'Custom Exception message';

        $this->testInvalidValue($errorMessage, $propertyPath, $expected);
    }

    public function test_it_gets_value_even_if_emtpy_value_detected()
    {
        // Given
        $expected = '';
        $propertyPath = 'firstName';
        $errorMessage = 'Custom Exception message';

        $this->testInvalidValue($errorMessage, $propertyPath, $expected);
    }

    private function testInvalidValue(string $errorMessage, string $propertyPath, $expectedValue)
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
        $actual = $sut->valueMustNotBeEmpty(
            $expectedValue,
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
