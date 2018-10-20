<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Entrance;

final class Address
{
    private $address;

    public static function fromString(string $address): self
    {
        return new self($address);
    }

    private function __construct(string $address)
    {
        $this->address = $address;
    }

    public function toString(): string
    {
        return $this->address;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->address === $other->address;
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
