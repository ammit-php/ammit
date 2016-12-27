# Ubiquitous Language Dictionary

Glossary gathering all terms used by our **Domain** (Domain Driven Design).
Aim is to have same terms in order to `avoid as far as possible any misunderstandings`.


# Application layer

#### Command

A [Command](http://verraes.net/2013/04/decoupling-symfony2-forms-from-entities/) is a simple well named [DTO](http://martinfowler.com/eaaCatalog/dataTransferObject.html) reflecting user **intention** to modify the system.
Command can be created from `HTML request`, `CLI arguments` or simply `directly from the code` (from Events, etc..).
It should reflect what the system is meant to do.
*Example:*
  - *RegisterUserCommand*
  - *DisableUserCommand*
  - *BookCargoCommand*

#### Query

A Query is a simple DTO like a `Command` but responsible for reading data from the system (**read only**).
Complex Query could be resolved by `Ammit` too.
*Example: In a search engine context (Filter / Sort / Pagination, etc..)*
See CQRS.

# User Interface (UI) layer

## Resolver

Perform **UI Validation** from `HTML request`, `CLI arguments` or simply `directly from the code` (from Events, etc..).
Then **map** data into a `Command`.
*Example: RegisterUserCommandResolver*

## Mapping

Extract data from `HTML request`, `CLI arguments` or simply `directly from the code` (from Events, etc..) in order to inject them into a `Command`.

## Validation

### 2 Process

#### UI Validation

Simple validation process aiming to allow data to be injected in the system.
It occurs before **Domain Validation** like a Firewall. In order to make sure safe data are entering into the Application/Domain. 
**UI Validation** messages are especially targeting developer (DX easing implementation)
*Example:*
 - *scalar type hinting (bool, int array, etc..)*
 - *DateTime string format so Domain directly takes care of \DateTime object with the right timezone*

#### Domain Validation

Complex validation process responsible for data coherency.
Data coherency from a business rule point of view. 
Meaning **Domain Validation** should exist without the need for prior **UI Validation**.
In DDD, Domain could be swapped into a new `Framework` (Symfony/Laravel/etc..) / `Transport layer` (CLI/Http/etc..).
So **Domain Validation** should be Framework/UI agnostic. It should only validate raw values.
**Domain Validation** messages are especially targeting end user (UX + i18n)

*Example:* 
- *Quantity should be a positive integer*
- *Email should be valid against a regex and against MX server*

### Value

#### Raw value

Our smallest scalar unit to be mapped/validated.

#### Request's attribute value

Value coming from a PSR-7 request attribute.
*Example: `$_POST` on the server*

#### Request's query string value

Value coming from a PSR-7 request query string.
*Example: `$_GET` on the server*

#### CLI's value ?

Value coming from a CLI input.
No PSR yet. You will have to use directly **raw value**

### Assertion Vs Validation ?

#### Assertion

Is the term used by our internal [beberlei/assert](https://github.com/beberlei/assert) assertion library.
It is directly throwing an Exception at 1st fail detected.

#### Validation 

Is the term used by our library API.
It is not directly throwing an Exception at 1st fail detected. 
But gather exceptions in order to display them all at the end of the **resolving process**.
Somewhat like a Form does.

# Theory

## Domain Driven Design

#### Pure

Represents an intent to do pure DDD by trying to follow the separation of concerns whatever the (short term) costs.
See `AbstractPureCommandResolver`

#### Pragmatic

Represents an intent to do pragmatic DDD.
You may have needs to put some Domain validation directly into your UI layer.
Sometimes we need to do some Rapid Application Development when prototyping.
And to take shortcuts knowing we will have to pay back our technical debt in a near future.
DDD should not prevent pragmatism. Or it will lead its adoption to be harder than it already is.
See `AbstractPragmaticCommandResolver`



