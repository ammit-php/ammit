<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\Stub\UI\CommandResolver\Pragmatic;

use Imedia\Ammit\UI\Resolver\AbstractPragmaticCommandResolver;
use Imedia\Ammit\UI\Resolver\Asserter\PragmaticRawValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\PragmaticRequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RawValueAsserter;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Units\Imedia\Ammit\Stub\Application\Command\RegisterUserCommand;

/**
 * Resolve a PSR-7 Request into a RegisterUserCommand (Data Transfer Object)
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RegisterUserCommandResolver extends AbstractPragmaticCommandResolver
{
    /**
     * @inheritdoc
     */
    public function resolve(ServerRequestInterface $request): RegisterUserCommand
    {
        $commandConstructorValues = $this->resolveRequestAsArray($request);

        return new RegisterUserCommand(...$commandConstructorValues);
    }

    /**
     * @inheritDoc
     * @param PragmaticRequestAttributeValueAsserter $attributeValueAsserter
     * @param PragmaticRawValueAsserter $rawValueAsserter
     */
    protected function validateThenMapAttributes(RequestAttributeValueAsserter $attributeValueAsserter, RawValueAsserter $rawValueAsserter, ServerRequestInterface $request): array
    {
        $id = $attributeValueAsserter->mustBeUuid(
            $request,
            'id'
        );

        $firstName = $attributeValueAsserter->mustBeString(
            $request,
            'firstName'
        );

        $lastName = $attributeValueAsserter->mustBeString(
            $request,
            'lastName'
        );

        $email = $attributeValueAsserter->mustBeString(
            $request,
            'email'
        );

        $commandConstructorValues = [
            $id,
            $firstName,
            $lastName,
            $email
        ];

        return $commandConstructorValues;
    }
}
