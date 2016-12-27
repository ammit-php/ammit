<?php
declare(strict_types=1);

namespace Imedia\Ammit\UI\Resolver\Validator;

use Imedia\Ammit\UI\Resolver\Exception\UIValidationException;

/**
 * Perform UI validation
 * Simple validation process aiming to allow data to be injected in the system.
 * It occurs before **Domain Validation** like a Firewall. In order to make sure safe data are entering into the Application/Domain.
 * UI Validation messages are especially targeting developer (DX easing implementation)
 * Example:
 *  - scalar type hinting (bool, int array, etc..)
 *  - DateTime string format so Domain directly takes care of \DateTime object with the right timezone
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
interface UIValidatorInterface
{
    /**
     * Create UI validation from context (Raw, QueryString Attribute, etc..)
     * @param string $message
     * @param string|null $propertyPath
     *
     * @return UIValidationException
     */
    public function createUIValidationException(string $message, string $propertyPath = null): UIValidationException;
}
