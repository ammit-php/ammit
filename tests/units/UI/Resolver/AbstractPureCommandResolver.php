<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver;

use Imedia\Ammit\UI\Resolver\Validator\RequestAttributeValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\RawValueValidator;
use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use Imedia\Ammit\UI\Resolver\Validator\RequestQueryStringValueValidator;
use mageekguy\atoum;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Units\Imedia\Ammit\Stub\Application\Command\RegisterUserCommand;
use Tests\Units\Imedia\Ammit\Stub\UI\CommandResolver\Pure\RegisterUserCommandResolver as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class AbstractPureCommandResolver extends atoum
{
    public function test_it_can_be_constructed_without_injection()
    {
        // Given
        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this->object($actual)
            ->isEqualTo(new RegisterUserCommand('42', 'Stephen', 'Hawking', 'stephen.hawking.me'))
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
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('42', 'Stephen', 'Hawking', 'stephen.hawking.me'))
            ->mock($validationEngineMock)
                    ->call('validateFieldValue')->exactly(4)
                    ->call('guardAgainstAnyUIValidationException')->once()
        ;
    }

    public function test_it_can_be_constructed_with_request_attribute_value_validator()
    {
        // Given
        $requestAttributeValueValidatorMock = $this->mockRequestAttributeValueValidator();
        $sut = new SUT(
            null,
            null,
            $requestAttributeValueValidatorMock,
            null
        );
        $requestMock = $this->mockServerRequest(
            [
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('42', 'azerty', 'azerty', 'azerty'))
            ->mock($requestAttributeValueValidatorMock)
                    ->call('mustBeString')->exactly(3)
        ;
    }

    public function test_it_can_be_constructed_with_request_query_string_value_validator()
    {
        // Given
        $requestQueryStringValueValidatorMock = $this->mockRequestQueryStringValueValidator();
        $sut = new SUT(
            null,
            null,
            null,
            $requestQueryStringValueValidatorMock
        );
        $requestMock = $this->mockServerRequest(
            [
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('azerty', 'Stephen', 'Hawking', 'stephen.hawking.me'))
            ->mock($requestQueryStringValueValidatorMock)
                    ->call('mustBeString')->exactly(1)
        ;
    }

    public function test_it_can_be_constructed_with_raw_value_validator()
    {
        // Given
        $rawValueValidatorMock = $this->mockRawValueValidator();
        $sut = new SUT(
            null,
            $rawValueValidatorMock,
            null,
            null
        );
        $requestMock = $this->mockServerRequest(
            [
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this
            ->object($actual)
                ->isEqualTo(new RegisterUserCommand('42', 'Stephen', 'Hawking', 'stephen.hawking.me'))
            ->mock($rawValueValidatorMock)
                    ->call('mustBeString')->exactly(4)
        ;
    }

    public function test_it_can_resolve_a_request()
    {
        // Given
        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'firstName' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
            ]
        );

        // When
        $actual = $sut->resolve($requestMock);

        // Then
        $this->object($actual)
            ->isEqualTo(new RegisterUserCommand('42', 'Stephen', 'Hawking', 'stephen.hawking.me'))
        ;
    }

    public function test_it_can_intercept_a_command_mapping_exception()
    {
        // Given
        $expected = [
            'errors' => [
                [
                    'status' => 406,
                    'source' => [
                        'pointer' => '/data/attributes/'
                    ],
                    'title' => 'Invalid Attribute',
                    'detail' => 'Array does not contain an element with key "firstName"'
                ]
            ]
        ];

        $sut = new SUT();
        $requestMock = $this->mockServerRequest(
            [
                'firstName2' => 'Stephen',
                'lastName' => 'Hawking',
                'email' => 'stephen.hawking.me',
            ],
            [
                'id' => '42',
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
                    ->call('getParsedBody')->once()
            ;

            return;
        }

        $this->throwException();
    }

    private function mockServerRequest(array $requestAttributes, array $requestQueryParams): ServerRequestInterface
    {
        $mock = new \mock\Psr\Http\Message\ServerRequestInterface();
        $this->calling($mock)->getParsedBody = $requestAttributes;
        $this->calling($mock)->getQueryParams = $requestQueryParams;

        return $mock;
    }

    private function mockUIValidationEngine(): \Imedia\Ammit\UI\Resolver\UIValidationEngine
    {
        $mock = new \mock\Imedia\Ammit\UI\Resolver\UIValidationEngine();
        $this->calling($mock)->validateFieldValue = null;
        $this->calling($mock)->guardAgainstAnyUIValidationException = null;

        return $mock;
    }

    private function mockRequestAttributeValueValidator(): RequestAttributeValueValidator
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Validator\RequestAttributeValueValidator();
        $this->calling($mock)->mustBeString = 'azerty';
        $this->calling($mock)->createUIValidationException =  'Prefix';

        return $mock;
    }

    private function mockRequestQueryStringValueValidator(): RequestQueryStringValueValidator
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Validator\RequestQueryStringValueValidator();
        $this->calling($mock)->mustBeString = 'azerty';
        $this->calling($mock)->createUIValidationException =  'Prefix';

        return $mock;
    }

    private function mockRawValueValidator(): RawValueValidator
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Validator\RawValueValidator();
        $this->calling($mock)->mustBeString = function ($value) { return $value; };

        return $mock;
    }

    private function throwException()
    {
        throw new \mageekguy\atoum\asserter\exception($this->variable(), 'CommandMappingException not thrown.');
    }
}
