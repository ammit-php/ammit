<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\Stub\Application\Command;

/**
 * This Command is not a Symfony CLI Command but a Command Pattern
 *        It's a DTO (Data Transfer Object) explicitly representing actor intention
 *        It represents all mandatory data necessary to perform the action
 *        Without exposing the Http Request useless for the domain
 * Your Bounded Context shall do nothing more than the listed Commands it contains
 * No logic here it is a simple POPO containing simple scalar variables
 *        We are not in the Domain yet
 *        No setter either, intention shall be immutable
 *        /!\ However we still have public fields here
 *        As Command will be validated/hydrated by a SF2 FormType as easier/clearer
 * For simplicity/readability we declare scalar validation (UI validation) directly in the Command
 * Try to avoid setting default data here as it would obfuscate original actor intention
 *
 * @see http://martinfowler.com/bliki/BoundedContext.html
 */
final class RegisterUserCommand
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $email;

    /** @var string */
    private $id;

    public function __construct(string $id, string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
