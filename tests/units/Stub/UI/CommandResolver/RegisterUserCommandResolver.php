<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\Stub\UI\CommandResolver;

use Imedia\Ammit\UI\Resolver\AbstractCommandResolver;
use Imedia\Ammit\UI\Resolver\Asserter\RequestAttributeValueAsserter;
use Imedia\Ammit\UI\Resolver\Asserter\RequestQueryValueAsserter;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Units\Imedia\Ammit\Stub\Application\Command\RegisterUserCommand;

/**
 * @author Jeremy FERRER <j.ferrer@imediafrance.fr>
 */
class RegisterUserCommandResolver extends AbstractCommandResolver
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
    protected function validateThenMapAttributes(RequestAttributeValueAsserter $attributeValueAsserter, RequestQueryValueAsserter $queryValueAsserter, ServerRequestInterface $request): array
    {
        $firstName = $attributeValueAsserter->attributeMustBeString(
            $request,
            'firstName'
        );

        $lastName = $attributeValueAsserter->attributeMustBeString(
            $request,
            'lastName'
        );

        $email = $attributeValueAsserter->attributeMustBeString(
            $request,
            'email'
        );

        $commandConstructorValues = [
            $firstName,
            $lastName,
            $email
        ];

        return $commandConstructorValues;
    }
}
