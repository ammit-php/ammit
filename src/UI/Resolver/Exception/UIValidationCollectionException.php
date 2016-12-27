<?php
declare(strict_types = 1);

namespace Imedia\Ammit\UI\Resolver\Exception;

/**
 * UI Validation Exception Collection
 *
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class UIValidationCollectionException extends AbstractNormalizableCommandResolverException
{
    /** @var UIValidationException[] */
    private $uiValidationExceptions = [];

    /**
     * @param UIValidationException[] $uiValidationExceptions
     */
    public function __construct(array $uiValidationExceptions)
    {
        $this->guardAgainstNoUIValidationException($uiValidationExceptions);

        parent::__construct(
            'Command Resolver UI Validation Exception',
            'collection'
        );

        foreach ($uiValidationExceptions as $uiValidationException) {
            $this->add($uiValidationException);
        }
    }

    private function add(UIValidationException $uiValidationException)
    {
        $this->uiValidationExceptions[] = $uiValidationException;
    }

    /**
     * @inheritdoc
     * See http://jsonapi.org/examples/#error-objects-basics
     * Example:
     * {
     *   "errors": [
     *     {
     *       "status": "403",
     *       "source": { "pointer": "/data/attributes/secret-powers" },
     *       "detail": "Editing secret powers is not authorized on Sundays."
     *     },
     *     {
     *      "source": { "parameter": "include" },
     *      "title":  "Invalid Query Parameter",
     *      "detail": "The resource does not have an `auther` relationship path."
     *     },
     *     {
     *       "status": "422",
     *       "source": { "pointer": "/data/attributes/volume" },
     *       "detail": "Volume does not, in fact, go to 11."
     *     },
     *     {
     *       "status": "500",
     *       "source": { "pointer": "/data/attributes/reputation" },
     *       "title": "The backend responded with an error",
     *       "detail": "Reputation service not responding after three requests."
     *     }
     *   ]
     * }
     */
    public function normalize(): array
    {
        $normalizedExceptions = ['errors' => []];

        foreach ($this->uiValidationExceptions as $exception) {
            $normalizedExceptions['errors'][] = $exception->normalize();
        }

        return $normalizedExceptions;
    }

    /**
     * @param UIValidationException[] $uiValidationExceptions
     */
    private function guardAgainstNoUIValidationException(array $uiValidationExceptions)
    {
        if (empty($uiValidationExceptions)) {
            throw new \LogicException('Can\'t create a UIValidationCollectionException without UIValidationException.');
        }
    }
}
