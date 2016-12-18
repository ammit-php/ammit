<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Exception;

use Imedia\Ammit\UI\Resolver\NormalizableInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
abstract class AbstractNormalizableCommandResolverException extends \InvalidArgumentException implements NormalizableInterface
{
    const PROPERTY_PATH_ROOT = 'root';

    /** @var string */
    private $propertyPath;

    /**
     * @inheritDoc
     */
    public function __construct(string $message, string $propertyPath = null)
    {
        parent::__construct($message, 0);

        if (null === $propertyPath) {
            $propertyPath = self::PROPERTY_PATH_ROOT;
        }

        $this->propertyPath = $propertyPath;
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    /**
     * @inheritdoc
     * Normalize the Exception into a ready to be JSON encoded array
     * Example:
     * {
     *     "status": "500",
     *     "source": { "pointer": "/data/attributes/reputation" },
     *     "title": "The backend responded with an error",
     *     "detail": "Reputation service not responding after three requests."
     * }
     */
    public function normalize(): array
    {
        return [
            'status' => 406,
            'source' => [
                'pointer' => '/data/attributes/' . $this->propertyPath
            ],
            'title' => 'Invalid Attribute',
            'detail' => $this->getMessage(),
        ];
    }
}
