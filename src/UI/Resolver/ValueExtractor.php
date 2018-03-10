<?php
declare(strict_types = 1);


namespace Imedia\Ammit\UI\Resolver;
use Imedia\Ammit\UI\Resolver\Validator\InvalidArgumentException;


class ValueExtractor
{
    /**
     * @param mixed $params
     * @param string $key
     *
     * @return mixed
     * @throws InvalidArgumentException If unable to extract
     */
    public function fromArray($params, string $key)
    {
        if (false === is_array($params)) {
            throw new InvalidArgumentException(
                sprintf('Value "%s" is not an array.', $params),
                0,
                null,
                $params
            );
        }

        if (false === array_key_exists($key, $params)) {
            throw new InvalidArgumentException(
                sprintf('Array does not contain an element with key "%s"', $key),
                0,
                null,
                $params
            );
        }

        return $params[$key];
    }
}
