<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\UI\Resolver\Validator;

use Imedia\Ammit\UI\Resolver\Exception\CommandMappingException;
use mageekguy\atoum;
use Imedia\Ammit\UI\Resolver\Validator\RequestAttributeValueValidator as SUT;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RequestAttributeValueValidator extends atoum
{
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
                'status' => 406,
                'source' => ['pointer' => '/data/attributes/'],
                'title' => 'Invalid Attribute',
                'detail' => 'Array does not contain an element with key "firstName"',
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

    public function test_invalid_attribute()
    {
        // Given
        $expected = [ 'errors' => [
                'status' => 406,
                'source' => ['pointer' => '/data/attributes/'],
                'title' => 'Invalid Attribute',
                'detail' => 'Array does not contain an element with key "firstName"',
            ]
        ];

        $sut = new SUT(
            $this->mockRawValueValidator()
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
    public function test_it_gets_value_from_psr7_request_even_if_not_string($propertyPath, $errorMessage, $value, array $expected)
    {
        // Given
        $this->testInvalidValue($value, $value);
    }

    /**
     * @dataProvider notBooleanDataProvider
     */
    public function test_it_gets_value_from_psr7_request_even_if_not_boolean($propertyPath, $errorMessage, $value, array $expected)
    {
        // Given
        $this->testInvalidValue($value, $value);
    }

    /**
     * @dataProvider notFloatDataProvider
     */
    public function test_it_gets_value_from_psr7_request_even_if_not_float($propertyPath, $errorMessage, $value, array $expected)
    {
        // Given
        $this->testInvalidValue($value, $value);
    }

    /**
     * @dataProvider notIntegerDataProvider
     */
    public function test_it_gets_value_from_psr7_request_even_if_not_integer($propertyPath, $errorMessage, $value, array $expected)
    {
        // Given
        $this->testInvalidValue($value, $value);
    }

    /**
     * @dataProvider notArrayDataProvider
     */
    public function test_it_gets_value_from_psr7_request_even_if_not_array($propertyPath, $errorMessage, $value, array $expected)
    {
        // Given
        $this->testInvalidValue($value, $value);
    }

    protected function notBooleanDataProvider(): array
    {
        $values = RawValueValidator::createAllScalars();
        unset($values['boolean']);

        return $values;
    }

    protected function notStringDataProvider(): array
    {
        $values = RawValueValidator::createAllScalars();
        unset($values['string']);

        return $values;
    }

    protected function notArrayDataProvider(): array
    {
        $values = RawValueValidator::createAllScalars();
        unset($values['array']);

        return $values;
    }

    protected function notFloatDataProvider(): array
    {
        $values = RawValueValidator::createAllScalars();
        unset($values['float']);

        return $values;
    }

    protected function notIntegerDataProvider(): array
    {
        $values = RawValueValidator::createAllScalars();
        unset($values['int']);

        return $values;
    }

    /**
     * @param mixed $value
     * @param mixed $expected
     */
    private function testInvalidValue($value, $expected)
    {
        $rawValueValidatorMock = $this->mockRawValueValidator();
        $sut = new SUT(
            $rawValueValidatorMock
        );

        $requestMock = $this->mockServerRequest(['firstName' => $value]);

        // When
        $actual = $sut->mustBeString(
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
            ->mock($rawValueValidatorMock)
                ->call('mustBeString')->once();
    }

    private function mockRawValueValidator(): \Imedia\Ammit\UI\Resolver\Validator\RawValueValidator
    {
        $this->mockGenerator->orphanize('__construct');
        $mock = new \mock\Imedia\Ammit\UI\Resolver\Validator\RawValueValidator();
        $this->calling($mock)->mustBeString = function ($value) { return $value; };

        return $mock;
    }

    private function mockServerRequest(array $requestAttributes): ServerRequestInterface
    {
        $mock = new \mock\Psr\Http\Message\ServerRequestInterface();
        $this->calling($mock)->getParsedBody = $requestAttributes;

        return $mock;
    }
}
