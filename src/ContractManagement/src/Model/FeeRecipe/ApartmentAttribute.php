<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\FeeRecipe;

final class ApartmentAttribute
{
    private $attribute;

    public static function fromString(string $attribute): self
    {
        return new self($attribute);
    }

    private function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    public function toString(): string
    {
        return $this->attribute;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->attribute === $other->attribute;
    }

    public function __toString(): string
    {
        return $this->attribute;
    }
}
