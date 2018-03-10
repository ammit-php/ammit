<?php
declare(strict_types = 1);

namespace AmmitPhp\Ammit\UI\Resolver;

use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationCollectionException;
use AmmitPhp\Ammit\UI\Resolver\Exception\UIValidationException;
use AmmitPhp\Ammit\UI\Resolver\Validator\InvalidArgumentException;
use AmmitPhp\Ammit\UI\Resolver\Validator\UIValidatorInterface;

class UIValidationEngine
{
    /** @var UIValidationException[] */
    private $interceptedUIValidationExceptions = [];

    /**
     * Restart the state of the UIValidationEngine
     * Clear all already intercepted UI Validation exceptions
     *
     * @return UIValidationEngine
     */
    public static function initialize(): UIValidationEngine
    {
        return new static();
    }

    /**
     * @throws UIValidationCollectionException
     */
    public function guardAgainstAnyUIValidationException()
    {
        if (count($this->interceptedUIValidationExceptions) > 0) {
            $exceptions = $this->interceptedUIValidationExceptions;
            $this->resetCommandResolverCollectionExceptions();

            throw new UIValidationCollectionException(
                $exceptions
            );
        }
    }

    /**
     * Validate if request field value is legit to be given to a Command
     * Catch UIValidationException in order to be able to process them later
     *
     * @param UIValidatorInterface $UIValidator
     * @param \Closure $validationFunction
     * @throws UIValidationException
     */
    public function validateFieldValue(UIValidatorInterface $UIValidator, \Closure $validationFunction)
    {
        try {
            $validationFunction->call($this);
        } catch (InvalidArgumentException $exception) {
            $this->addUIValidationException(
                $UIValidator,
                $exception->getMessage(),
                $exception->getPropertyPath()
            );
        }
    }

    /**
     * [DX] If developer forgot to call validateFieldValue()
     * @inheritDoc
     */
    public function __destruct()
    {
        $this->guardAgainstAnyUIValidationException();
    }

    private function __construct()
    {
        $this->resetCommandResolverCollectionExceptions();
    }

    private function addUIValidationException(UIValidatorInterface $uiValidator, string $message, string $propertyPath)
    {
        $this->interceptedUIValidationExceptions[] = $uiValidator->createUIValidationException(
            $message,
            $propertyPath
        );
    }

    private function resetCommandResolverCollectionExceptions()
    {
        $this->interceptedUIValidationExceptions = [];
    }
}
