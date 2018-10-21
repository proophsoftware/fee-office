<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\Resolver;

use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentAttribute;
use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentAttributeLabel;
use Prooph\EventMachine\Messaging\Message;
use React\Promise\Deferred;

final class ApartmentAttributeLabels
{
    public function __invoke(Message $getLabels, Deferred $deferred): void
    {
        $labels = [];

        foreach (ApartmentAttributeLabel::MAP as $id => $label) {
            $labels[] = [ApartmentAttribute::LABEL_ID => $id, ApartmentAttribute::LABEL => $label];
        }

        $deferred->resolve($labels);
    }
}
