<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class ApartmentAttribute implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const LABEL_ID = 'labelId';
    const LABEL = 'label';
    const VALUE = 'value';

    public static function fromLabelAndValue(ApartmentAttributeLabel $label, ApartmentAttributeValue $value): self
    {
        return self::fromRecordData([
            self::LABEL => $label,
            self::VALUE => $value
        ]);
    }

    /**
     * @var ApartmentAttributeLabel
     */
    private $label;

    /**
     * @var ApartmentAttributeValue
     */
    private $value;

    /**
     * @return ApartmentAttributeLabel
     */
    public function label(): ApartmentAttributeLabel
    {
        return $this->label;
    }

    /**
     * @return ApartmentAttributeValue
     */
    public function value(): ApartmentAttributeValue
    {
        return $this->value;
    }
}
