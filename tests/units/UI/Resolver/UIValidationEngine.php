<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface;
use mageekguy\atoum;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use Imedia\Ammit\UI\Resolver\UIValidationEngine as SUT;
use Tests\Units\Imedia\Ammit\Stub\ClosureFactory;

class UIValidationEngine extends atoum
{
    public function test_it_can_be_initialized()
    {
        // Given
        $sut = SUT::initialize();

        // When
        $sut->guardAgainstAnyUIValidationException();

        // Then
        $this
            ->boolean(true)
                ->isTrue('Should not throw exception');
    }


    public function test_it_throws_ui_validation_exception_asynchronously()
    {
        // Given
        $sut = SUT::initialize();

        $propertyPath = 'firstName';
        $sut->validateFieldValue(
            $this->mockUIValidator(),
            ClosureFactory::createInvalidClosure($propertyPath)
        );

        $expected = [
            'errors' => [
                ['azerty']
            ]
        ];

        try {
            $sut->guardAgainstAnyUIValidationException();
        } catch (UIValidationCollectionException $e) {
            $actual = $e->normalize();
            $this->array($actual)
                ->isEqualTo($expected);

            return;
        }

        $this->throwError('UIValidationCollectionException not thrown.');
    }

    public function test_it_throws_ui_validation_exception_on_destruction()
    {
        // Given
        $sut = SUT::initialize();

        $propertyPath = 'firstName';
        $sut->validateFieldValue(
            $this->mockUIValidator(),
            ClosureFactory::createInvalidClosure($propertyPath)
        );

        $expected = [
            'errors' => [
                ['azerty']
            ]
        ];

        try {
            $sut->__destruct();
        } catch (UIValidationCollectionException $e) {
            $actual = $e->normalize();
            $this->array($actual)
                ->isEqualTo($expected);

            return;
        }

        $this->throwError('UIValidationCollectionException not thrown.');
    }

    private function throwError(string $message)
    {
        throw new \mageekguy\atoum\asserter\exception(
            $this->variable(),
            $message
        );
    }

    private function mockUIValidator(): UIValidatorInterface
    {
        $this->mockGenerator->orphanize('__construct');
        $mockUIValidationException = new \mock\Imedia\Ammit\UI\Resolver\Exception\UIValidationException();
        $this->calling($mockUIValidationException)->normalize = ['azerty'];

        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Validator\UIValidatorInterface();
        $this->calling($mock)->createUIValidationException = $mockUIValidationException;

        return $mock;
    }
}
