<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class DateValidation
{
    const FORMAT_SIMPLE = 'Y-m-d';

    public function isDateValid(string $dateString): bool
    {
        if (!$this->isDateValidAgainstRegex($dateString)) {
            return false;
        }

        $date = \DateTime::createFromFormat(self::FORMAT_SIMPLE, $dateString);
        if (false === $date) {
            return false;
        }

        if ($date->format(self::FORMAT_SIMPLE) === $dateString) {
            return true;
        }

        return false;
    }

    private function isDateValidAgainstRegex(string $date): bool
    {
        if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date)) {
            return true;
        }

        return false;
    }
}
