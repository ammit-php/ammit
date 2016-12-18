<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Asserter;

use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use mageekguy\atoum;
use Imedia\Ammit\UI\Resolver\Asserter\RequestAttributeValueAsserter as SUT;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestAttributeValueAsserter extends atoum
{
    public function test_it_gets_value_from_psr7_request()
    {
        // Given
        $expected = null;
        $propertyPath = 'firstName';

        $sut = new SUT(
            $this->mockRequestQueryValueAsserter()
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
        $expected = new CommandMappingException('Array does not contain an element with key "firstName"', 'root');
        $expected = $expected->normalize();

        $sut = new SUT(
            $this->mockRequestQueryValueAsserter()
        );

        $requestMock = $this->mockServerRequest(['firstName2' => 42]);

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

    public function test_invalid_attribute()
    {
        // Given
        $expected = new CommandMappingException('Array does not contain an element with key "firstName"', 'root');
        $expected = $expected->normalize();

        $sut = new SUT(
            $this->mockRequestQueryValueAsserter()
        );

        $requestMock = $this->mockServerRequest(['firstName2' => 42]);

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

    /**
     * @dataProvider notStringDataProvider
     */
    public function test_it_gets_value_from_psr7_request_even_if_not_string($value)
    {
        // Given
        $this->testInvalidValue($value, $value);
    }

    protected function notStringDataProvider(): array
    {
        $values = RequestQueryValueAsserter::createAllScalars();
        unset($values['string']);

        return $values;
    }

    /**
     * @param mixed $value
     * @param mixed $expected
     */
    private function testInvalidValue($value, $expected)
    {
        $requestQueryValueAsserterMock = $this->mockRequestQueryValueAsserter();
        $sut = new SUT(
            $requestQueryValueAsserterMock
        );

        $requestMock = $this->mockServerRequest(['firstName' => $value]);

        // When
        $actual = $sut->attributeMustBeString(
            $requestMock,
            'firstName',
            'Custom Exception message'
        );

        // Then
        $this
            ->variable($actual)
                ->isEqualTo($expected)
            ->mock($requestMock)
                ->call('getParsedBody')->once()
            ->mock($requestQueryValueAsserterMock)
                ->call('valueMustBeString')->once();
    }

    private function mockRequestQueryValueAsserter($value = null): \Imedia\Ammit\UI\Resolver\Asserter\RequestQueryValueAsserter
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Asserter\RequestQueryValueAsserter();
        $this->calling($mock)->valueMustBeString = function ($value) { return $value; };

        return $mock;
    }

    private function mockServerRequest(array $requestAttributes): ServerRequestInterface
    {
        $mock = new \mock\Psr\Http\Message\ServerRequestInterface();
        $this->calling($mock)->getParsedBody = $requestAttributes;

        return $mock;
    }
}
