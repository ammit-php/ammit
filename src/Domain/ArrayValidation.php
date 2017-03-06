<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;


/**
 * @author Steven SELVINI <s.selvini@imediafrance.fr>
 */
class ArrayValidation
{
    /**
     * @param mixed $array Array to validate
     * @return bool
     */
    public function isArrayValid($array): bool
    {
        return is_array($array);
    }
}
