<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\BankAccount;

final class BIC
{
    private $bic;

    public static function fromString(string $bic): self
    {
        return new self($bic);
    }

    private function __construct(string $bic)
    {
        //@TODO add BIC validation
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
