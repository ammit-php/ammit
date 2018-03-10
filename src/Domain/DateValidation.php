<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

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
        if (!$this->isValidDateAgainstRegex($dateString)) {
            return false;
        }

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
        if (!$this->isValidDateTimeAgainstRegex($dateString)) {
            return false;
        }

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

    private function isValidDateAgainstRegex($string): bool
    {
        if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$string)) {
            return true;
        }

        return false;
    }

    private function isValidDateTimeAgainstRegex($string): bool
    {
        if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T(0[0-9]|1[0-9]|2[0-3]):\d\d:\d\d\+\d\d:\d\d$/',$string)) {
            return true;
        }

        return false;
    }
}
