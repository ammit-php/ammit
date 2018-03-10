<?php
declare(strict_types = 1);

namespace AmmitPhp\Ammit\Domain;

class StringValidation
{
    /**
     * String between X chars and Y chars
     * @param mixed $value String to validate
     * @param int $minLength
     * @param int $maxLength
     * @param string $encoding
     *
     * @return bool
     */
    public function isStringBetweenValid($value, int $minLength, int $maxLength, $encoding = 'utf8'): bool
    {
        if (false ===is_string($value)) {
            return false;
        }

        $length = mb_strlen($value, $encoding);
        if ($length < $minLength) {
            return false;
        }

        if ($length > $maxLength) {
            return false;
        }

        return true;
    }
}
