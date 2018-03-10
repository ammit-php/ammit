<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

class BooleanValidation
{
    /**
     * Valid against UUID format
     * @param mixed $value String to validate
     * @return bool
     */
    public function isBooleanValid($value): bool
    {
        if (in_array($value, [true, false, 1, 0, '1', '0', 'true', 'false'], true)) {
            return true;
        }

        return false;
    }
}
