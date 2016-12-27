<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Exception;

use Imedia\Ammit\UI\Resolver\NormalizableInterface;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
abstract class AbstractNormalizableCommandResolverException extends \InvalidArgumentException implements NormalizableInterface
{
    const SOURCE_RAW = null;
    const SOURCE_ATTRIBUTE = 'pointer';
    const SOURCE_PARAMETER = 'parameter';

    /** @var string */
    private $propertyPath;

    /** @var string|null */
    private $source;

    /**
     * @inheritDoc
     */
    public function __construct(string $message, string $propertyPath = null, string $source = null)
    {
        parent::__construct($message, 0);

        $this->propertyPath = self::cleanPropertyPath($propertyPath);
        $this->source = $source;
    }

    /**
     * From raw value directly injected
     */
    public static function fromRaw(string $message, string $propertyPath = null): AbstractNormalizableCommandResolverException
    {
        return new static(
            $message,
            self::cleanPropertyPath($propertyPath),
            self::SOURCE_RAW
        );
    }

    /**
     * From query string parameter ($_GET)
     */
    public static function fromParameter(string $message, string $propertyPath = null): AbstractNormalizableCommandResolverException
    {
        return new static(
            $message,
            self::cleanPropertyPath($propertyPath),
            self::SOURCE_PARAMETER
        );
    }

    /**
     * From attribute parameter ($_POST)
     */
    public static function fromAttribute(string $message, string $propertyPath = null): AbstractNormalizableCommandResolverException
    {
        return new static($message,
            self::cleanPropertyPath($propertyPath),
            self::SOURCE_ATTRIBUTE
        );
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    /**
     * @return null|string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @inheritdoc
     * Normalize the Exception into a ready to be JSON encoded array
     * Example for Attribute $_POST:
     * {
     *     "status": "406",
     *     "source": { "pointer": "/data/attributes/firstName" },
     *     "title": "Invalid Attribute",
     *     "detail": "Array does not contain an element with key firstName"
     * }
     *
     * Example for parameter $_GET
     * {
     *     "status": "406",
     *     "source": { "parameter": "firstName" },
     *     "title":  "Invalid Query Parameter",
     *     "detail": "Array does not contain an element with key firstName"
     *     }
     */
    public function normalize(): array
    {
        return [
            'status' => 406,
            'source' => $this->createSourceNode(
                $this->propertyPath,
                $this->source
            ),
            'title' => $this->createTitleNode($this->source),
            'detail' => $this->getMessage(),
        ];
    }

    private function createSourceNode(string $propertyPath, string $source = null): array
    {
        if (self::SOURCE_ATTRIBUTE === $source) {
            return [
                'pointer' => '/data/attributes/' . $propertyPath
            ];
        }

        return [
            'parameter' => $propertyPath
        ];
    }

    private function createTitleNode(string $source = null): string
    {
        if (self::SOURCE_PARAMETER === $source) {
            return 'Invalid Query Parameter';
        }

        if (self::SOURCE_RAW === $source) {
            return 'Invalid Parameter';
        }

        return 'Invalid Attribute';
    }

    private static function cleanPropertyPath(string $propertyPath = null): string
    {
        if (null === $propertyPath) {
            return $propertyPath = '';
        }

        return $propertyPath;
    }
}
