<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\ContactCard;

final class LastName
{
    private $lastName;

    public static function fromString(string $lastName): self
    {
        return new self($lastName);
    }

    private function __construct(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function toString(): string
    {
        return $this->lastName;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->lastName === $other->lastName;
    }

    public function __toString(): string
    {
        return $this->lastName;
    }
}
