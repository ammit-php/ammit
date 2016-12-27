<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Asserter\PragmaticRawValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\PragmaticRequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use mageekguy\atoum;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Units\Imedia\Ammit\Stub\Application\Command\RegisterUserCommand;
use Tests\Units\Imedia\Ammit\Stub\UI\CommandResolver\Pragmatic\RegisterUserCommandResolver as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class AbstractPragmaticCommandResolver extends atoum
{
    public function test_it_can_be_constructed_without_injection()
    {
        // Given
        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this->object($actual)
            ->isEqualTo(new RegisterUserCommand('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Stephen', 'Hawking', 'stephen.hawking.me'))
        ;
    }

    public function test_it_can_be_constructed_with_ui_validation_engine()
    {
        // Given
        $validationEngineMock = $this->mockUIValidationEngine();
        $sut = new SUT(
            $validationEngineMock
        );
        $requestMock = $this->mockServerRequest(
            [
                'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Stephen', 'Hawking', 'stephen.hawking.me'))
            ->mock($validationEngineMock)
                    ->call('validateFieldValue')->exactly(4)
                    ->call('guardAgainstAnyUIValidationException')->once()
        ;
    }

    public function test_it_can_be_constructed_with_request_attribute_value_asserter()
    {
        // Given
        $requestAttributeValueAsserteMock = $this->mockRequestAttributeValueAsserter();
        $sut = new SUT(
            null,
            $requestAttributeValueAsserteMock
        );
        $requestMock = $this->mockServerRequest(
            [
                'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'azerty', 'azerty', 'azerty'))
            ->mock($requestAttributeValueAsserteMock)
                ->call('mustBeString')->thrice()
                ->call('mustBeUuid')->once()
        ;
    }

    public function test_it_can_be_constructed_with_raw_value_asserter()
    {
        // Given
        $rawValueAsserterMock = $this->mockRawValueAsserter();
        $sut = new SUT(
            null,
            null,
            $rawValueAsserterMock
        );
        $requestMock = $this->mockServerRequest(
            [
                'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Stephen', 'Hawking', 'stephen.hawking.me'))
            ->mock($rawValueAsserterMock)
                    ->call('mustBeString')->thrice()
                    ->call('mustBeUuid')->once()
        ;
    }

    public function test_it_can_resolve_a_request()
    {
        // Given
        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this->object($actual)
            ->isEqualTo(new RegisterUserCommand('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Stephen', 'Hawking', 'stephen.hawking.me'))
        ;
    }

    public function test_it_can_intercept_a_command_mapping_exception()
    {
        // Given
        $expected = new CommandMappingException('Array does not contain an element with key "firstName"', 'root');
        $expected = $expected->normalize();

        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
                'firstName2' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        try {
            $sut->resolve($requestMock);
        } catch (CommandMappingException $e) {
            $actual = $e->normalize();

            // Then
            $this
                ->array($actual)
                    ->isEqualTo($expected)
                ->mock($requestMock)
                    ->call('getParsedBody')->twice() // Then throw exception
            ;

            return;
        }

        $this->throwException();
    }

    public function test_it_can_intercept_a_ui_validation_exception()
    {
        // Given
        $expected = [
            'errors' => [
                [
                    'status' => 406,
                    'source' => ['pointer' => '/data/attributes/id'],
                    'title' => 'Invalid Attribute',
                    'detail' => 'Value "42" is not a valid UUID.',
                ]
            ]
        ];

        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'id' => '42',
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ]
        );

        // When
        try {
            $sut->resolve($requestMock);
        } catch (UIValidationCollectionException $e) {
            $actual = $e->normalize();

            // Then
            $this
                ->array($actual)
                    ->isEqualTo($expected)
                ->mock($requestMock)
                    ->call('getParsedBody')->exactly(4)
            ;

            return;
        }

        $this->throwException();
    }

    private function mockServerRequest(array $requestAttributes): ServerRequestInterface
    {
        $mock = new \mock\Psr\Http\Message\ServerRequestInterface();
        $this->calling($mock)->getParsedBody = $requestAttributes;

        return $mock;
    }

    private function mockUIValidationEngine(): \Imedia\Ammit\UI\Resolver\UIValidationEngine
    {
        $mock = new \mock\Imedia\Ammit\UI\Resolver\UIValidationEngine();
        $this->calling($mock)->validateFieldValue = null;
        $this->calling($mock)->guardAgainstAnyUIValidationException = null;

        return $mock;
    }

    private function mockRequestAttributeValueAsserter(): PragmaticRequestAttributeValueAsserter
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Asserter\PragmaticRequestAttributeValueAsserter();
        $this->calling($mock)->mustBeString = 'azerty';
        $this->calling($mock)->mustBeUuid = 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa';

        return $mock;
    }

    private function mockRawValueAsserter(): PragmaticRawValueAsserter
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Asserter\PragmaticRawValueAsserter();
        $this->calling($mock)->mustBeString = function ($value) { return $value; };
        $this->calling($mock)->mustBeUuid = function ($value) { return $value; };

        return $mock;
    }

    private function throwException()
    {
        throw new \mageekguy\atoum\asserter\exception($this->variable(), 'CommandMappingException not thrown.');
    }
}
