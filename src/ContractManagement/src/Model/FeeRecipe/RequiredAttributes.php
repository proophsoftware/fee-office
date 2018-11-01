<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\FeeRecipe;

final class RequiredAttributes
{
    /**
     * @var ApartmentAttribute[]
     */
    private $attributes;

    public static function emptyList(): self
    {
        return new self();
    }

    public static function fromAttributes(ApartmentAttribute ...$attributeLabels): self
    {
        return new self(...$attributeLabels);
    }

    public static function fromArray(array $data): self
    {
        $attrs = array_map(function (string $name): ApartmentAttribute {
            return ApartmentAttribute::fromString($name);
        }, $data);

        return new self(...$attrs);
    }

    private function __construct(ApartmentAttribute ...$attributeLabels)
    {
        $this->attributes = $attributeLabels;
    }

    public function toArray(): array
    {
        return array_map(function (ApartmentAttribute $attr): string {
            return $attr->toString();
        }, $this->attributes);
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->toArray() === $other->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
