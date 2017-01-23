<img src="https://cloud.githubusercontent.com/assets/2279794/21160379/ef90f812-c184-11e6-99da-add0658f2baf.png" align="right" width="230px" height="206px" vspace="20" />
# Ammit 

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/imediafrance/ammit/master/LICENSE)
[![Build Status](https://travis-ci.com/imediafrance/ammit.svg?token=JcSB2GZng3ssVpoUAxup&branch=master)](https://travis-ci.com/imediafrance/ammit)


[DDD] A light, stable and framework agnostic Command resolver library

# Currently Battle Tested (not yet tagged)

A [Command](http://verraes.net/2013/04/decoupling-symfony2-forms-from-entities/) is a simple well named [DTO](http://martinfowler.com/eaaCatalog/dataTransferObject.html) reflecting user **intention**. 

Consequently it shall be **immutable**.

<img src="/docs/RegisterUserCommand.png" align="left" vspace="20" />

  - *RegisterUserCommand*
  - *DisableUserCommand*
  - *BookCargoCommand*
  

#### What the lib does ?

- It provides a helper to easily **extract** scalar data from a PSR-7 HTTP Request (or a CLI input) in order to instantiate an immutable Command.
- It allows to implement clean Commands (no public field).
- It is designed to be a simple **UI Validation** framework based on the stable and [dependency free](https://en.wikipedia.org/wiki/Dependency_hell) [beberlei/assert](https://github.com/beberlei/assert) assertion library.
- It is designed to ease segregating UI validation Vs Domain validation concerns

------------------

![Simple Spec](/docs/specification-simple.png)



#### How to use it ?

Example: 

Implement a `RegisterUserCommandResolver` which will map a PSR-7 `ServerRequestInterface` into a `RegisterUserCommand`.
Before creating `RegisterUserCommand` it will perform a UI validation.

*RegisterUserController.php*
```php
$registerUserCommandResolver = new RegisterUserCommandResolver();
try {
    $command = $registerUserCommandResolver->resolve($request);
} catch (AbstractNormalizableCommandResolverException $e) {
    // Return a JSON error following jsonapi.org's format
    // @see http://jsonapi.org/examples/#error-objects-basics
    return JsonResponse::fromJsonString(
        json_encode(
            $e->normalize()
        ), 
        406
    );
}

try {
    $this->userService->registerUser($command);
} catch(DomainException $e) {
   // ...
}
// ...
```

*RegisterUserCommandResolver.php*
```php
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

        // We are using variadic function here (https://wiki.php.net/rfc/variadics)
        return new RegisterUserCommand(...$commandConstructorValues);
    }

    /**
     * @inheritDoc
     */
    protected function validateThenMapAttributes(ServerRequestInterface $request): array
    {
        // $id = $_GET['id']
        $id = $this->queryStringValueValidator->mustBeString(
            $request,
            'id'
        );

        // $firstName = $_POST['firstName']
        $firstName = $this->attributeValueValidator->mustBeString(
            $request,
            'firstName'
        );

        // $lastName = $_POST['lastName']
        $lastName = $this->attributeValueValidator->mustBeString(
            $request,
            'lastName'
        );

        // $email = $_POST['email']
        $email = $this->attributeValueValidator->mustBeString(
            $request,
            'email'
        );

        // Will be injected directly in RegisterUserCommand::__construct(...$args)
        // as variadic function
        $commandConstructorValues = [
            $id,
            $firstName,
            $lastName,
            $email
        ];

        return $commandConstructorValues;
    }
}
```

Use it with Symfony: http://symfony.com/doc/current/request/psr7.html

Use it with Laravel: TBA

#### What the lib does not ?

- It is not designed to be a Symfony [Form Component](https://symfony.com/doc/current/components/form.html) replacement.
- It is not designed to create complex validation. It's aim is to validate simple scalar. Yet it still allows "[pragmatic](https://github.com/imediafrance/ammit#pragmatic-)" complex UI validation for prototyping/RAD.
- It is not designed to use PHP reflection. It is only meant to use Command constructor.

#### Why ?

We were using Symfony [Form Component](https://symfony.com/doc/current/components/form.html) to map and validate HTTP Requests to our Commands.

But it was way too complex and [hacky](https://github.com/webdevilopers/php-ddd/issues/5). And too tempting to put our Domain validation into FormType. Then to "forget" to put it back into our Domain.

Furthermore we wanted to anticipate [Immutable class](https://wiki.php.net/rfc/immutability).

#### How does it work ?

![Complete Spec](/docs/specification-complete.png)

It is using `\Closure` internally in order to be able to catch all `\Exception`. 
Otherwise it would display only 1 validation issue. And we want to see all validation issues at once like with Forms.

#### Pragmatic ?

You may have needs to put some Domain validation in your UI.
Sometimes we need to do some Rapid Application Development when prototyping.
And to take shortcuts knowing we will have to pay back our technical debt in a near future.

With **Ammit** you would use our `AbstractPragmaticCommandhenResolver` (**Pragmatic**) instead of our `AbstractPureCommandResolver` (**Pure**) helper.
It will allow you to use more complex validation like `uuid` validation for example:

```php
$email = $attributeValueValidator->mustBeUuid(
    $request,
    'id'
);
```

A validation is missing. You can still inject your own based on [beberlei/assert](https://github.com/beberlei/assert) assertion library.


#### Want to contribute ?

Read [UBIQUITOUS_LANGUAGE_DICTIONARY.md](UBIQUITOUS_LANGUAGE_DICTIONARY.md)

Init docker container: `docker-compose up -d`

Composer install: `docker-compose run --rm composer install`

Use container: `docker/bin/php -v` (first do `chmod +x docker/bin/php`)

Add Unit Test then: `docker/bin/php bin/atoum`

#### Ammit ?

> Ammit, an ancient egyptian goddess involved in heart weighting. She was devouring souls of human judged to be not pure enough to continue their voyage towards Osiris and immortality.

