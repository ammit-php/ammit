<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Exception;

class CommandMappingException extends AbstractNormalizableCommandResolverException
{
    /**
     * @inheritdoc
     * See http://jsonapi.org/examples/#error-objects-basics
     * Example:
     * {
     *   "errors": [
     *     {
     *       "status": "406",
     *       "source": { "pointer": "/data/attributes/firstName" },
     *       "title": "Invalid Attribute",
     *       "detail": "Array does not contain an element with key firstName"
     *     }
     *   ]
     * }
     */
    public function normalize(): array
    {
        return [
            'errors' => [
                parent::normalize()
            ]
        ];
    }
}
