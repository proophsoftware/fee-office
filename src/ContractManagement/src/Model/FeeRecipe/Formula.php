<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\FeeRecipe;

final class Formula
{
    private $formula;

    public static function fromString(string $formula): self
    {
        return new self($formula);
    }

    private function __construct(string $formula)
    {
        $this->formula = $formula;
    }

    public function toString(): string
    {
        return $this->formula;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->formula === $other->formula;
    }

    public function __toString(): string
    {
        return $this->formula;
    }
}
