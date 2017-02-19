<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class DateValidation
{
    const FORMAT_SIMPLE = 'Y-m-d';
    const FORMAT_RFC3339 = \DateTime::RFC3339;

    /**
     * Valid against Y-m-d format
     * @param string $dateString String to validate
     * @return bool
     */
    public function isDateValid(string $dateString): bool
    {
        $date = $this->createDateFromString($dateString);
        if (false === $date) {
            return false;
        }

        if ($date->format(self::FORMAT_SIMPLE) === $dateString) {
            return true;
        }

        return false;
    }

    public function isDateTimeValid(string $dateString): bool
    {
        $date = $this->createDateTimeFromString($dateString);
        if (false === $date) {
            return false;
        }

        if ($date->format(self::FORMAT_RFC3339) === $dateString) {
            return true;
        }

        return false;
    }

    /**
     * @return \DateTime|false
     */
    public function createDateFromString(string $dateString)
    {
        return \DateTime::createFromFormat(self::FORMAT_SIMPLE, $dateString);
    }

    /**
     * @return \DateTime|false
     */
    public function createDateTimeFromString(string $dateString)
    {
        return \DateTime::createFromFormat(self::FORMAT_RFC3339, $dateString);
    }
}
