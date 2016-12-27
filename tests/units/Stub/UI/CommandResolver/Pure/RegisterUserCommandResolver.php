<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\Stub\UI\CommandResolver\Pure;

use Imedia\Ammit\UI\Resolver\AbstractPureCommandResolver;
use Imedia\Ammit\UI\Resolver\Asserter\RequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RawValueAsserter;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Units\Imedia\Ammit\Stub\Application\Command\RegisterUserCommand;

/**
 * Resolve a PSR-7 Request into a RegisterUserCommand (Data Transfer Object)
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class RegisterUserCommandResolver extends AbstractPureCommandResolver
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
     */
    protected function validateThenMapAttributes(RequestAttributeValueAsserter $attributeValueAsserter, RawValueAsserter $rawValueAsserter, ServerRequestInterface $request): array
    {
        $id = $attributeValueAsserter->mustBeString(
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
