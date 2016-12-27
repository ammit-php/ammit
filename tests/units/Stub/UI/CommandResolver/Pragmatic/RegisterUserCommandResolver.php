<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\Stub\UI\CommandResolver\Pragmatic;

use Imedia\Ammit\UI\Resolver\AbstractPragmaticCommandResolver;
use Imedia\Ammit\UI\Resolver\Validator\PragmaticRawValueValidator;
use Imedia\Ammit\UI\Resolver\Validator\PragmaticRequestAttributeValueValidator;
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
     * @param PragmaticRequestAttributeValueValidator $attributeValueValidator
     * @param PragmaticRawValueValidator $rawValueValidator
     */
    protected function validateThenMapAttributes(ServerRequestInterface $request): array
    {
        $id = $this->attributeValueValidator->mustBeUuid(
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
