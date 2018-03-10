<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Exception;

use mageekguy\atoum;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException as SUT;

class UIValidationCollectionException extends atoum
{
    public function test_it_cant_be_created_empty()
    {
        $this->exception(
            function () {
                new SUT([]);
            }
        )->isInstanceOf('\LogicException')
            ->message
                ->contains('Can\'t create a UIValidationCollectionException without UIValidationException.')
        ;
    }

    /**
     * @dataProvider uiValidationExceptionsDataProvider
     */
    public function test_it_can_normalize_one_exception(array $exceptions, array $expectedNormalized)
    {
        // Given
        $expectedMessage = 'Command Resolver UI Validation Exception';
        $expectedPropertyPath = 'collection';

        // When
        $sut = new SUT($exceptions);

        // Then
        $this
            ->array($sut->normalize())
                ->isEqualTo($expectedNormalized)
            ->phpString($sut->getMessage())
                ->isEqualTo($expectedMessage)
            ->phpString($sut->getPropertyPath())
                ->isEqualTo($expectedPropertyPath)
        ;
    }

    protected function uiValidationExceptionsDataProvider()
    {
        return [
            [
                'exceptions' => [
                    $this->mockUIValidationException(['A'])
                ],
                'expectedNormalized' => [
                    'errors' => [
                        ['A']
                    ]
                ]
            ],
            [
                'exceptions' => [
                    $this->mockUIValidationException(['A']),
                    $this->mockUIValidationException(['B'])
                ],
                'expectedNormalized' => [
                    'errors' => [
                        ['A'],
                        ['B']
                    ]
                ]
            ]
        ];
    }

    private function mockUIValidationException(array $normalized): \Imedia\Ammit\UI\Resolver\Exception\UIValidationException
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Exception\UIValidationException();
        $this->calling($mock)->normalize = $normalized;

        return $mock;
    }
}
