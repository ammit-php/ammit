<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

class UuidValidation
{
    /**
     * Valid against UUID format
     * @param string $string String to validate
     * @return bool
     */
    public function isUuidValid(string $string): bool
    {
        if ($this->isValidUuidAgainstRegex($string)) {
            return true;
        }

        return false;
    }

    private function isValidUuidAgainstRegex($string): bool
    {
        if (preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $string)) {
            return true;
        }

        return false;
    }
}
