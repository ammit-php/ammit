<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Validator;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;
use mageekguy\atoum;

use Imedia\Ammit\UI\Resolver\Validator\RawValueValidator as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RawValueValidator extends atoum
{
    public static function createAllScalars(): array
    {
        $propertyPath = 'firstName';
        $errorMessage = 'Custom Exception message';
        $expectedNormalizedException = [
            'status' => 406,
            'source' => ['parameter' => $propertyPath],
            'title' => 'Invalid Parameter',
            'detail' => $errorMessage,
        ];

        return [
            'null' => [
                'propertyPath' => $propertyPath,
                'errorMessage' => $errorMessage,
                'value' => null,
                'expectedNormalizedException' => $expectedNormalizedException
            ],
            'string' => [
                'propertyPath' => $propertyPath,
                'errorMessage' => $errorMessage,
                'value' => 'azerty',
                'expectedNormalizedException' => $expectedNormalizedException
            ],
            'array' => [
                'propertyPath' => $propertyPath,
                'errorMessage' => $errorMessage,
                'value' => [],
                'expectedNormalizedException' => $expectedNormalizedException
            ],
            'int' => [
                'propertyPath' => $propertyPath,
                'errorMessage' => $errorMessage,
                'value' => 42,
                'expectedNormalizedException' => $expectedNormalizedException
            ],
            'float' => [
                'propertyPath' => $propertyPath,
                'errorMessage' => $errorMessage,
                'value' => 13.9,
                'expectedNormalizedException' => $expectedNormalizedException
            ],
            'boolean' => [
                'propertyPath' => $propertyPath,
                'errorMessage' => $errorMessage,
                'value' => true,
                'expectedNormalizedException' => $expectedNormalizedException
            ],
        ];
    }

    /**
     * @dataProvider notStringDataProvider
     */
    public function test_it_gets_value_even_if_not_string_value_detected($propertyPath, $errorMessage, $value, array $expectedNormalizedException)
    {
        $this->testInvalidValue(
            $errorMessage,
            $propertyPath,
            $value,
            $expectedNormalizedException,
            'mustBeString'
        );
    }

    protected function notStringDataProvider(): array
    {
        $values = $this->createAllScalars();
        unset($values['string']);

        return $values;
    }

    /**
     * @dataProvider notBooleanDataProvider
     */
    public function test_it_gets_value_even_if_not_boolean_value_detected($propertyPath, $errorMessage, $value, array $expectedNormalizedException)
    {
        $this->testInvalidValue(
            $errorMessage,
            $propertyPath,
            $value,
            $expectedNormalizedException,
            'mustBeBoolean'
        );
    }

    protected function notBooleanDataProvider(): array
    {
        $values = $this->createAllScalars();
        unset($values['boolean']);

        return $values;
    }

    private function testInvalidValue(string $errorMessage, string $propertyPath, $value, $expectedNormalizedException, string $methodToTest, UIValidatorInterface $parentValidation = null)
    {
        $expectedNormalizedExceptions = [
            'errors' => [
                $expectedNormalizedException
            ]
        ];

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->$methodToTest(
            $value,
            $propertyPath,
            $parentValidation,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($value);

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
            $this->variable(),
            'UIValidationCollectionException not thrown.'
        );
    }
}
