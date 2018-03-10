<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\UI\Resolver\Validator;

use AmmitPhp\Ammit\UI\Resolver\Exception\CommandMappingException;
use mageekguy\atoum;
use AmmitPhp\Ammit\UI\Resolver\Validator\RequestAttributeValueValidator as SUT;
use Psr\Http\Message\ServerRequestInterface;

class RequestAttributeValueValidator extends atoum
{
    /**
     * @dataProvider validatorMethodDataProvider
     */
    public function test_it_extends_raw_value_validator(string $methodName, $value, $expected)
    {
        $path = 'a';
        $rawValueValidatorMock = $this->mockRawValueValidator();
        $sut = new SUT($rawValueValidatorMock);

        $requestMock = $this->mockServerRequest([$path => $value]);

        $this->class($sut)
            ->hasMethod($methodName)
            ;
        $actual = $sut->$methodName($requestMock, $path);

        $this->mock($rawValueValidatorMock)
            ->call($methodName)
                ->withAnyArguments()
                ->once()
            ;

        $this->variable($actual)
            ->isEqualTo($expected)
        ;
    }

    protected function validatorMethodDataProvider()
    {
        $data = [
            ['mustBeBoolean', true, true],
            ['mustBeBooleanOrEmpty', '', null],
            ['mustBeArray', [], []],
            ['mustBeDate', '2016-01-01', \DateTime::createFromFormat('Y-m-d', '2016-01-01')->setTime(0, 0, 0)],
            ['mustBeInteger', 1, 1],
            ['mustBeIntegerOrEmpty', '', null],
            ['mustBeString', 'a', 'a'],
            ['mustBeStringOrEmpty', '', null],
            ['mustBeFloat', 3.14, 3.14],
            ['mustBeDateTime', '2017-01-01T00%3A00%3A00%2B00%3A00', \DateTime::createFromFormat(\DateTime::RFC3339, '2017-01-01T00:00:00+00:00')], // url encoded
            ['mustBeDateTimeOrEmpty', '', null],
        ];

        if (count($data) != count(RawValueValidator::getSutMethodNames())) {
            throw new \LogicException('RequestAttributeValueValidator is not implementing all RawValueValidator validators.');
        }

        return $data;
    }

    public function test_it_gets_value_from_psr7_request()
    {
        // Given
        $expected = null;
        $propertyPath = 'firstName';

        $sut = new SUT(
            $this->mockRawValueValidator()
        );

        $requestMock = $this->mockServerRequest([$propertyPath => $expected]);

        // When
        $actual = $sut->extractValueFromRequestAttribute(
            $requestMock,
            $propertyPath
        );

        // Then
        $this
            ->variable($actual)
                ->isEqualTo($expected)
            ->mock($requestMock)
                ->call('getParsedBody')->once()
        ;
    }

    public function test_it_throws_exception_when_psr7_request_attribute_is_absent()
    {
        // Given
        $expected = [
            'errors' => [
                [
                    'status' => 406,
                    'source' => ['pointer' => '/data/attributes/'],
                    'title' => 'Invalid Attribute',
                    'detail' => 'Array does not contain an element with key "firstName"',
                ]
            ]
        ];

        $sut = new SUT(
            $this->mockRawValueValidator()
        );

        $requestMock = $this->mockServerRequest([]);

        // Then
        try {
            $sut->extractValueFromRequestAttribute(
                $requestMock,
                'firstName'
            );
        } catch (CommandMappingException $e) {
            $actual = $e->normalize();
            $this
                ->array($actual)
                    ->isEqualTo($expected)
                ->mock($requestMock)
                    ->call('getParsedBody')->once()
            ;

            return;
        }

        throw new \mageekguy\atoum\asserter\exception($this->variable(), 'CommandMappingException not thrown.');
    }

    private function mockRawValueValidator(): \AmmitPhp\Ammit\UI\Resolver\Validator\RawValueValidator
    {
        $this->mockGenerator->orphanize('__construct');
        $mockUiValidationEngineMock = new \mock\AmmitPhp\Ammit\UI\Resolver\UIValidationEngine();

        $mock = new \mock\AmmitPhp\Ammit\UI\Resolver\Validator\RawValueValidator($mockUiValidationEngineMock);

        return $mock;
    }

    private function mockServerRequest(array $requestAttributes): ServerRequestInterface
    {
        $mock = new \mock\Psr\Http\Message\ServerRequestInterface();
        $this->calling($mock)->getParsedBody = $requestAttributes;

        return $mock;
    }
}
