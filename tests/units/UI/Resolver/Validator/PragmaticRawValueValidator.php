<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\UI\Resolver\Validator;

use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use AmmitPhp\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use AmmitPhp\Ammit\UI\Resolver\Validator\PragmaticRawValueValidator as SUT;

class PragmaticRawValueValidator extends atoum
{
    public function test_it_gets_value_even_if_string_empty_detected()
    {
        // Given
        $value = '';
        $propertyPath = 'firstName';
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
        $actual = $sut->mustBeStringNotEmpty(
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

    public function test_it_gets_value_even_if_valid()
    {
        // Given
        $value = 'not empty';
        $propertyPath = 'firstName';
        $errorMessage = 'Custom Exception message';

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeStringNotEmpty(
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

    public function test_it_gets_value_even_if_bad_value_against_regex_detected()
    {
        // Given
        $value = 'not a number';
        $pattern = '/\d{4}/';
        $propertyPath = 'year';
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
        $actual = $sut->mustBeValidAgainstRegex(
            $value,
            $pattern,
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

    public function test_it_gets_value_even_if_valid_value_against_regex()
    {
        // Given
        $value = '2017';
        $pattern = '/\d{4}/';
        $propertyPath = 'year';
        $errorMessage = 'Custom Exception message';

        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeValidAgainstRegex(
            $value,
            $pattern,
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
            $this->variable(false),
            'UIValidationCollectionException not thrown.'
        );
    }
}
