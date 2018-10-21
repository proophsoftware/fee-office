<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\Finder;

use FeeOffice\RealtyRegistration\Model\Apartment;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceApartments;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Prooph\EventMachine\Persistence\DocumentStore;

final class ApartmentFinder
{
    /**
     * @var DocumentStore
     */
    private $documentStore;

    /**
     * @var string
     */
    private $collectionName;

    public function __construct(string $collectionName, DocumentStore $documentStore)
    {
        $this->collectionName = $collectionName;
        $this->documentStore = $documentStore;
    }

    public function getEntranceApartments(EntranceId $entranceId): EntranceApartments
    {
        $cursor = $this->documentStore->filterDocs($this->collectionName, new DocumentStore\Filter\EqFilter(
            Apartment\State::ENTRANCE_ID,
            $entranceId->toString()
        ));

        return new EntranceApartments($entranceId, ...array_map(function (array $apartment) {
            return Apartment\State::fromArray($apartment);
        }, iterator_to_array($cursor)));
    }
}
