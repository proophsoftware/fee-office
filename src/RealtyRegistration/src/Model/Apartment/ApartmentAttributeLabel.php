<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;


final class ApartmentAttributeLabel
{
    public const AREA_ID = 'f1c7fabc-4685-4329-99d3-64af9a6a7cc9';
    public const AREA = 'area';

    public const BODY_COUNT_ID = '7489e2b5-42ee-4ae6-b023-8cf791188b13';
    public const BODY_COUNT = 'bodyCount';

    public const MAP = [
        self::AREA_ID => self::AREA,
        self::BODY_COUNT_ID => self::BODY_COUNT,
    ];

    private $id;

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public static function area(): self
    {
        return new self(self::AREA_ID);
    }

    public static function bodyCount(): self
    {
        return new self(self::BODY_COUNT_ID);
    }

    private function __construct(string $id)
    {
        if(!array_key_exists($id, self::MAP)) {
            throw new \InvalidArgumentException("Unknown apartment attribute label id given. Got $id.");
        }

        $this->id = $id;
    }

    public function label(): string
    {
        return self::MAP[$this->id];
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
