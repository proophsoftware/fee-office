<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\FeeRecipe;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FeeRecipeId
{
    private $recipeId;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $recipeId): self
    {
        return new self(Uuid::fromString($recipeId));
    }

    private function __construct(UuidInterface $recipeId)
    {
        $this->recipeId = $recipeId;
    }

    public function toString(): string
    {
        return $this->recipeId->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->recipeId->equals($other->recipeId);
    }

    public function __toString(): string
    {
        return $this->recipeId->toString();
    }
}
