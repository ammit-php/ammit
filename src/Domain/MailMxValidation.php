<?php
declare(strict_types = 1);

namespace Imedia\Ammit\Domain;

use Assert\Assertion;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
class MailMxValidation
{
    public function isEmailHostValid(string $emailAddress): bool
    {
        $host = substr($emailAddress, strrpos($emailAddress, '@') + 1);

        return $this->isHostValid($host);
    }

    public function isEmailFormatValid(string $emailAddress): bool
    {
        try {
            Assertion::email($emailAddress);
        } catch (\Assert\AssertionFailedException $e) {
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
