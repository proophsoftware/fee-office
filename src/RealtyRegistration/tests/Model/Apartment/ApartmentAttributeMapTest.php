<?php
declare(strict_types=1);

namespace FeeOfficeTest\RealtyRegistration\Model\Apartment;

use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentAttribute;
use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentAttributeLabel;
use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentAttributeMap;
use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentAttributeValue;
use FeeOfficeTest\RealtyRegistration\BaseTestCase;

final class ApartmentAttributeMapTest extends BaseTestCase
{
    /**
     * @test
     */
    public function it_can_be_converted_to_array_and_back()
    {
        $map = ApartmentAttributeMap::fromAttributes(
            ApartmentAttribute::fromLabelAndValue(
                ApartmentAttributeLabel::area(),
                ApartmentAttributeValue::fromString('120')
            ),
            ApartmentAttribute::fromLabelAndValue(
                ApartmentAttributeLabel::bodyCount(),
                ApartmentAttributeValue::fromString('2')
            )
        );

        $mapArr = $map->toArray();

        $this->assertEquals([
            ApartmentAttributeLabel::area()->toString() => '120',
            ApartmentAttributeLabel::bodyCount()->toString() => '2'
        ], $mapArr);

        $mapCopy = ApartmentAttributeMap::fromArray($mapArr);

        $this->assertTrue($map->equals($mapCopy));
    }

    /**
     * @test
     */
    public function it_can_be_empty()
    {
        $emptyMap = ApartmentAttributeMap::asEmptyMap();

        $this->assertEquals([], $emptyMap->toArray());
    }

    /**
     * @test
     */
    public function it_is_immutable()
    {
        $map = ApartmentAttributeMap::fromAttributes(
            ApartmentAttribute::fromLabelAndValue(
                ApartmentAttributeLabel::area(),
                ApartmentAttributeValue::fromString('120')
            ),
            ApartmentAttribute::fromLabelAndValue(
                ApartmentAttributeLabel::bodyCount(),
                ApartmentAttributeValue::fromString('2')
            )
        );

        $changedMap = $map->set(
            ApartmentAttribute::fromLabelAndValue(
                ApartmentAttributeLabel::area(),
                ApartmentAttributeValue::fromString('60')
            )
        );

        $this->assertEquals('60', $changedMap->get(ApartmentAttributeLabel::area())->value()->toString());
        $this->assertEquals('120', $map->get(ApartmentAttributeLabel::area())->value()->toString());
    }
}
