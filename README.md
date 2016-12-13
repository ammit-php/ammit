<img src ="https://raw.githubusercontent.com/imediafrance/ammit/master/weighing_of_the_heart.jpg" alt="Ammit" align="right"/>
Ammit
=====

[DDD] A light stable & framework agnostic Command resolver library

> Ammit, an ancient egyptian goddess involved in heart weighting. She was devouring souls of human judged to be not pure enough to continue their voyage towards Osiris and immortality.

A [Command](http://verraes.net/2013/04/decoupling-symfony2-forms-from-entities/) is a simple [DTO](http://martinfowler.com/eaaCatalog/dataTransferObject.html) reflecting user **intention**. Consequently it shall be **immutable**.

#### What the lib does

- Provide a helper to easily **extract** scalar data from a PSR-7 HTTP Request (or a CLI input) in order to instantiate an immutable Command.
- Simple **UI Validation** framework based on the stable & [dependency free](https://en.wikipedia.org/wiki/Dependency_hell) [beberlei/assert](https://github.com/beberlei/assert) assertion library.

#### What the lib does not

- Symfony [Form Component](https://symfony.com/doc/current/components/form.html) replacement
- Complex validation, it is only aim to validate simple scalar
- Using PHP reflection

#### Why ?

We were using Symfony [Form Component](https://symfony.com/doc/current/components/form.html) to map and validate our HTTP Request to our Command.
But it was way too complex and [hacky](https://github.com/webdevilopers/php-ddd/issues/5).
We wanted to anticipate [Immutable class](https://wiki.php.net/rfc/immutability).


##### How to use it 
Use it with Symfony: http://symfony.com/doc/current/request/psr7.html
