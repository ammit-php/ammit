<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver;

/**
 * Allow to transform this Object into a ready to be serialized Array
 * Then this normalized array will be easily Serializable in the desired format
 */
interface NormalizableInterface
{
    /**
     * Transform the implementation into a ready to be serialized array
     *
     * @return array
     */
    public function normalize(): array;
}
