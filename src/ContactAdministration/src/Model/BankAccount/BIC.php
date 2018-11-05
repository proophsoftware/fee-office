<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\BankAccount;

use FeeOffice\ContactAdministration\Model\Exception\InvalidBIC;

final class BIC
{
    public const VALID_PATTERN = '/^[a-z]{6}[2-9a-z][0-9a-np-z]([a-z0-9]{3}|x{3})?$/i';

    private $bic;

    public static function fromString(string $bic): self
    {
        return new self($bic);
    }

    private function __construct(string $bic)
    {
        if(!preg_match(self::VALID_PATTERN, $bic)) {
            throw InvalidBIC::formatCheckFailed($bic);
        }

        $this->bic = $bic;
    }

    public function toString(): string
    {
        return $this->bic;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->bic === $other->bic;
    }

    public function __toString(): string
    {
        return $this->bic;
    }
}
