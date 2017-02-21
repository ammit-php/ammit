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
     * @dataProvider booleanDataProvider
     */
    public function test_it_gets_value_even_if_boolean_valid($value, $expected)
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

    protected function booleanDataProvider(): array
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

    public function test_it_gets_value_even_if_bad_boolean_detected()
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

    /**
     * @dataProvider notArrayDataProvider
     */
    public function test_it_gets_value_even_if_not_array_value_detected($propertyPath, $errorMessage, $value, array $expectedNormalizedException)
    {
        $this->testInvalidValue(
            $errorMessage,
            $propertyPath,
            $value,
            $expectedNormalizedException,
            'mustBeArray'
        );
    }

    /**
     * @dataProvider floatDataProvider
     */
    public function test_it_gets_value_even_if_float_valid($value, $expected)
    {
        // Given
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeInteger(
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

    protected function floatDataProvider(): array
    {
        return [
            ['value' => 42, 'expected' => 42.0],
            ['value' => '42', 'expected' => 42.0],
            ['value' => '42.0', 'expected' => 42.0],
            ['value' => 42.0, 'expected' => 42.0],
        ];
    }

    public function test_it_gets_value_even_if_bad_float_detected()
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

    protected function notArrayDataProvider(): array
    {
        $values = $this->createAllScalars();
        unset($values['array']);

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

    public function test_it_gets_value_even_if_bad_date_detected()
    {
        // Given
        $value = 'bad-date';
        $propertyPath = 'birthDate';
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
        $actual = $sut->mustBeDate(
            $value,
            $propertyPath,
            null,
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

    public function test_it_gets_value_even_if_date_valid()
    {
        // Given
        $value = '2017-01-01';
        $propertyPath = 'birthDate';
        $errorMessage = 'Custom Exception message';

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeDate(
            $value,
            $propertyPath,
            null,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($value);

        $uiValidationEngine->guardAgainstAnyUIValidationException();
    }

    public function test_it_gets_value_even_if_bad_datetime_detected()
    {
        // Given
        $value = 'bad-date';
        $propertyPath = 'birthDate';
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
        $actual = $sut->mustBeDateTime(
            $value,
            $propertyPath,
            null,
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

    public function test_it_gets_value_even_if_datetime_valid()
    {
        // Given
        $value = '2017-01-01T00:00:00+00:00';
        $propertyPath = 'birthDate';
        $errorMessage = 'Custom Exception message';

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeDateTime(
            $value,
            $propertyPath,
            null,
            $errorMessage
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($value);

        $uiValidationEngine->guardAgainstAnyUIValidationException();
    }

    private function throwError()
    {
        throw new \mageekguy\atoum\asserter\exception(
            $this->variable(true),
            'UIValidationCollectionException not thrown.'
        );
    }
}
