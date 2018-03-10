<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

class MailMxValidation
{
    public function isEmailHostValid(string $emailAddress): bool
    {
        $host = substr($emailAddress, strrpos($emailAddress, '@') + 1);

        return $this->isHostValid($host);
    }

    public function isEmailFormatValid(string $emailAddress): bool
    {
        if (! filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {

            return false;
        }

        return true;
    }

    /**
     * Check DNS Records for MX type.
     */
    private static function isMxValid(string $host): bool
    {
        return checkdnsrr($host, 'MX');
    }

    /**
     * Check if one of MX, A or AAAA DNS RR exists.
     */
    private function isHostValid(string $host): bool
    {
        return $this->isMxValid($host) || (checkdnsrr($host, 'A') || checkdnsrr($host, 'AAAA'));
    }
}
