<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;

final class ApartmentAttributeValue
{
    private $value;

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
