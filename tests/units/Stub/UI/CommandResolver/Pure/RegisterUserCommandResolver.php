<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\UI\CommandResolver\Pure;

use AmmitPhp\Ammit\UI\Resolver\AbstractPureCommandResolver;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Units\AmmitPhp\Ammit\Stub\Application\Command\RegisterUserCommand;

/**
 * Resolve a PSR-7 Request into a RegisterUserCommand (Data Transfer Object)
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
    protected function validateThenMapAttributes(ServerRequestInterface $request): array
    {
        $id = $this->queryStringValueValidator->mustBeString(
            $request,
            'id'
        );

        $firstName = $this->attributeValueValidator->mustBeString(
            $request,
            'firstName'
        );

        $lastName = $this->attributeValueValidator->mustBeString(
            $request,
            'lastName'
        );

        $email = $this->attributeValueValidator->mustBeString(
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
