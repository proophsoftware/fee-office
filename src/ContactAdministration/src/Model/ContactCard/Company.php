<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\ContactCard;

final class Company
{
    private $company;

    public static function fromString(string $company): self
    {
        return new self($company);
    }

    private function __construct(string $company)
    {
        $this->company = $company;
    }

    public function toString(): string
    {
        return $this->company;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->company === $other->company;
    }

    public function __toString(): string
    {
        return $this->company;
    }
}
