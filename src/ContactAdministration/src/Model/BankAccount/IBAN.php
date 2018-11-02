<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\BankAccount;

final class IBAN
{
    private $iban;

    public static function fromString(string $iban): self
    {
        return new self($iban);
    }

    private function __construct(string $iban)
    {
        //@TODO: Add IBAN validation
        $this->iban = $iban;
    }

    public function toString(): string
    {
        return $this->iban;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->iban === $other->iban;
    }

    public function __toString(): string
    {
        return $this->iban;
    }
}
