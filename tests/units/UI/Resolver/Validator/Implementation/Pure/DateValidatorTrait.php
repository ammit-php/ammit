<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Validator\Implementation\Pure;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine;
use mageekguy\atoum;

use Tests\Units\Imedia\Ammit\Stub\UI\Resolver\Validator\Implementation\Pure\DateValidatorStub as SUT;

class DateValidatorTrait extends atoum
{
    /**
     * @dataProvider goodDateProvider
     */
    public function test_it_gets_value_even_if_valid_date($value, $expected)
    {
        // Given
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeDate(
            $value,
            'birthDate',
            null,
            'Custom Exception message'
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($expected);

        $uiValidationEngine->guardAgainstAnyUIValidationException();
    }

    protected function goodDateProvider(): array
    {
        return [
            ['value' => '2015-01-01', 'expected' => \DateTime::createFromFormat('Y-m-d', '2015-01-01')->setTime(0, 0, 0)],
            ['value' => '2013-01-31', 'expected' => \DateTime::createFromFormat('Y-m-d', '2013-01-31')->setTime(0, 0, 0)],
        ];
    }

    /**
     * @dataProvider badDateProvider
     */
    public function test_it_gets_value_even_if_invalid_date($value, $expected)
    {
        // Given
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
            ->isEqualTo($expected);

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

    protected function badDateProvider(): array
    {
        return [
            ['value' => false, 'expected' => $this->createDefaultDateTime()],
            ['value' => null, 'expected' => $this->createDefaultDateTime()],
            ['value' => 'azerty', 'expected' => $this->createDefaultDateTime()],
            ['value' => 42, 'expected' => $this->createDefaultDateTime()],
        ];
    }

    /**
     * @dataProvider goodDateTimeProvider
     */
    public function test_it_gets_value_even_if_valid_datetime($value, $expected)
    {
        // Given
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeDateTime(
            $value,
            'birthDate',
            null,
            'Custom Exception message'
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($expected);

        $uiValidationEngine->guardAgainstAnyUIValidationException();
    }

    protected function goodDateTimeProvider(): array
    {
        return [
            ['value' => '2016-01-01T00:00:00+00:00', 'expected' => \DateTime::createFromFormat(\DateTime::RFC3339, '2016-01-01T00:00:00+00:00')],
            ['value' => '2018-01-31T00:00:00+00:00', 'expected' => \DateTime::createFromFormat(\DateTime::RFC3339, '2018-01-31T00:00:00+00:00')],
        ];
    }

    /**
     * @dataProvider badDateTimeProvider
     */
    public function test_it_gets_value_even_if_invalid_datetime($value, $expected)
    {
        // Given
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
            ->isEqualTo($expected);

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

    protected function badDateTimeProvider(): array
    {
        return [
            ['value' => '2014-01-01', 'expected' => $this->createDefaultDateTime()],
            ['value' => false, 'expected' => $this->createDefaultDateTime()],
            ['value' => null, 'expected' => $this->createDefaultDateTime()],
            ['value' => 'azerty', 'expected' => $this->createDefaultDateTime()],
            ['value' => 42, 'expected' => $this->createDefaultDateTime()],
        ];
    }

    /**
     * @dataProvider goodDateTimeOrNullProvider
     */
    public function test_it_gets_value_even_if_datetime_or_null($value, $expected)
    {
        // Given
        $uiValidationEngine = UIValidationEngine::initialize();
        $sut = new SUT($uiValidationEngine);

        // When
        $actual = $sut->mustBeDateTimeOrEmpty(
            $value,
            'birthDate',
            null,
            'Custom Exception message'
        );

        // Then
        $this
            ->variable($actual)
            ->isEqualTo($expected);

        $uiValidationEngine->guardAgainstAnyUIValidationException();
    }

    protected function goodDateTimeOrNullProvider(): array
    {
        return [
            ['value' => '2016-01-01T00:00:00+00:00', 'expected' => \DateTime::createFromFormat(\DateTime::RFC3339, '2016-01-01T00:00:00+00:00')],
            ['value' => '', 'expected' => null],
        ];
    }

    private function throwError()
    {
        throw new \mageekguy\atoum\asserter\exception(
            $this->variable(true),
            'UIValidationCollectionException not thrown.'
        );
    }

    private function createDefaultDateTime(): \DateTime
    {
        $date = new \DateTime();

        return $date->setTime(0, 0, 0);
    }
}
