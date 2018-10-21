<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;

final class ApartmentAttributeMap
{
    private $map = [];

    public static function fromAttributes(ApartmentAttribute ...$attributes): self
    {
        return new self(...$attributes);
    }

    public static function asEmptyMap(): self
    {
        return new self();
    }

    public static function fromArray(array $map): self
    {
        foreach ($map as $id => $value) {
            $map[$id] = ApartmentAttribute::fromRecordData([
                ApartmentAttribute::LABEL => ApartmentAttributeLabel::fromString($id),
                ApartmentAttribute::VALUE => ApartmentAttributeValue::fromString($value),
            ]);
        }
        return new self(...array_values($map));
    }

    private function __construct(ApartmentAttribute ...$attributes)
    {
        foreach ($attributes as $attribute) {
            $this->map[$attribute->label()->toString()] = $attribute;
        }
    }

    public function contains(ApartmentAttributeLabel $label): bool
    {
        return array_key_exists($label->toString(), $this->map);
    }

    public function get(ApartmentAttributeLabel $label): ?ApartmentAttribute
    {
        return $this->map[$label->toString()] ?? null;
    }

    public function set(ApartmentAttribute $attribute): self
    {
        $map = $this->map;
        $map[$attribute->label()->toString()] = $attribute;
        return new self(...array_values($map));
    }

    public function toArray(): array
    {
        return array_map(function (ApartmentAttribute $attribute) {
            return $attribute->value()->toString();
        }, $this->map);
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
