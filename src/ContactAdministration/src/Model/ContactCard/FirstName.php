<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\ContactCard;

final class FirstName
{
    private $firstName;

    public static function fromString(string $firstName): self
    {
        return new self($firstName);
    }

    private function __construct(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function toString(): string
    {
        return $this->firstName;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->firstName === $other->firstName;
    }

    public function __toString(): string
    {
        return $this->firstName;
    }
}
