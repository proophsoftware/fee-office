<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;

final class ApartmentNumber
{
    private $number;

    public static function fromString(string $number): self
    {
        return new self($number);
    }

    private function __construct(string $number)
    {
        $this->number = $number;
    }

    public function toString(): string
    {
        return $this->number;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->number === $other->number;
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
