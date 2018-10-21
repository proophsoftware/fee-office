<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\Resolver;

use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Apartment;
use FeeOffice\RealtyRegistration\Model\Building as BuildingModel;
use FeeOffice\RealtyRegistration\Model\Building\BuildingId;
use FeeOffice\RealtyRegistration\Model\Entrance;
use FeeOffice\RealtyRegistration\Model\Exception\BuildingNotFound;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\EventMachine\Messaging\Message;
use Prooph\EventMachine\Persistence\DocumentStore;
use React\Promise\Deferred;

final class Building
{
    const ENTRANCES = 'entrances';
    const APARTMENTS = 'apartments';

    /**
     * @var DocumentStore
     */
    private $documentStore;

    /**
     * @var string
     */
    private $buildingColName;

    /**
     * @var string
     */
    private $entranceColName;

    /**
     * @var string
     */
    private $apartmentColName;

    public function __construct(DocumentStore $documentStore, string $buildingColName, string $entranceColName, string $apartmentColName)
    {
        $this->documentStore = $documentStore;
        $this->buildingColName = $buildingColName;
        $this->entranceColName = $entranceColName;
        $this->apartmentColName = $apartmentColName;
    }

    public function __invoke(Message $getBuildings, Deferred $deferred): void
    {
        try {
            if($buildingId = $getBuildings->getOrDefault(Payload::BUILDING_ID, false)) {
                $this->resolveWithBuildingFilter(BuildingId::fromString($buildingId), $deferred);
                return;
            }

            if($entranceId = $getBuildings->getOrDefault(Payload::ENTRANCE_ID, false)) {
                $this->resolveWithEntranceFilter(Entrance\EntranceId::fromString($entranceId), $deferred);
                return;
            }

            if($apartmentId = $getBuildings->getOrDefault(Payload::APARTMENT_ID, false)) {
                $this->resolveWithApartmentFilter(Apartment\ApartmentId::fromString($apartmentId), $deferred);
                return;
            }
        } catch (\Throwable $err) {
            $deferred->reject($err);
            return;
        }


        $deferred->reject(
            new \InvalidArgumentException("You have to provide at least one filter.", StatusCodeInterface::STATUS_BAD_REQUEST)
        );
    }

    private function resolveWithBuildingFilter(BuildingId $buildingId, Deferred $deferred): void
    {
        $building = $this->loadBuilding($buildingId);
        $entrances = $this->loadEntrancesByBuildingId($buildingId);

        $entranceIds = array_map(function (array $entrance) {
            return Entrance\EntranceId::fromString($entrance[Entrance\State::ENTRANCE_ID]);
        }, $entrances);

        $entranceApartmentsMap = $this->loadApartmentsByEntranceIds(...$entranceIds);

        foreach ($entrances as &$entrance) {
            $entrance[self::APARTMENTS] = $entranceApartmentsMap[$entrance[Entrance\State::ENTRANCE_ID]] ?? [];
        }

        $building[self::ENTRANCES] = $entrances;

        $deferred->resolve($building);
    }

    private function resolveWithEntranceFilter(Entrance\EntranceId $entranceId, Deferred $deferred): void
    {
        $entrance = $this->loadEntrance($entranceId);

        $building = $this->loadBuilding(BuildingId::fromString($entrance[Entrance\State::BUILDING_ID]));

        $entranceApartmentsMap = $this->loadApartmentsByEntranceIds($entranceId);

        $entranceData = $this->extractEntranceDataFromDoc($entrance);

        $entranceData[self::APARTMENTS] = $entranceApartmentsMap[$entranceId->toString()] ?? [];

        $building[self::ENTRANCES] = [$entranceData];

        $deferred->resolve($building);
    }

    private function resolveWithApartmentFilter(Apartment\ApartmentId $apartmentId, Deferred $deferred): void
    {
        $apartment = $this->loadApartment($apartmentId);

        $entrance = $this->loadEntrance(Entrance\EntranceId::fromString($apartment[Apartment\State::ENTRANCE_ID]));

        $building = $this->loadBuilding(BuildingId::fromString($entrance[Entrance\State::BUILDING_ID]));

        $entranceData = $this->extractEntranceDataFromDoc($entrance);

        $entranceData[self::APARTMENTS] = [$this->extractApartmentDataFromDoc($apartment)];

        $building[self::ENTRANCES] = [$entranceData];

        $deferred->resolve($building);
    }

    private function loadBuilding(BuildingId $buildingId): array
    {
        $doc = $this->documentStore->getDoc($this->buildingColName, $buildingId->toString());

        if(!$doc) {
            throw BuildingNotFound::withBuildingId($buildingId);
        }

        return [
            BuildingModel\State::BUILDING_ID => $doc[BuildingModel\State::BUILDING_ID],
            BuildingModel\State::NAME => $doc[BuildingModel\State::NAME],
        ];
    }

    private function loadEntrance(Entrance\EntranceId $entranceId): array
    {
        $doc = $this->documentStore->getDoc($this->entranceColName, $entranceId->toString());

        if(!$doc) {
            throw BuildingNotFound::forEntrance($entranceId);
        }

        return $doc;
    }

    private function loadApartment(Apartment\ApartmentId $apartmentId): array
    {
        $doc = $this->documentStore->getDoc($this->apartmentColName, $apartmentId->toString());

        if(!$doc) {
            throw BuildingNotFound::forApartment($apartmentId);
        }

        return $doc;
    }


    private function loadEntrancesByBuildingId(BuildingId $buildingId): array
    {
        $cursor = $this->documentStore->filterDocs($this->entranceColName, new DocumentStore\Filter\EqFilter(
            Entrance\State::BUILDING_ID,
            $buildingId->toString()
        ));

        return array_map([$this, 'extractEntranceDataFromDoc'], iterator_to_array($cursor));
    }

    private function loadApartmentsByEntranceIds(Entrance\EntranceId ...$ids): array
    {
        $filter = null;

        foreach ($ids as $entranceId) {
            $eqFilter = new DocumentStore\Filter\EqFilter(Apartment\State::ENTRANCE_ID, $entranceId->toString());

            if($filter) {
                $filter = new DocumentStore\Filter\OrFilter($filter, $eqFilter);
            } else {
                $filter = $eqFilter;
            }
        }

        if(!$filter) {
            throw new \RuntimeException(__METHOD__ . ' invoked without at least one entrance Id!');
        }

        $cursor = $this->documentStore->filterDocs($this->apartmentColName, $filter);

        $entranceApartmentsMap = [];

        foreach ($cursor as $doc) {
            $entranceId = $doc[Apartment\State::ENTRANCE_ID];
            if(!array_key_exists($entranceId, $entranceApartmentsMap)) {
                $entranceApartmentsMap[$entranceId] = [];
            }

            $entranceApartmentsMap[$entranceId][] = $this->extractApartmentDataFromDoc($doc);
        }

        return $entranceApartmentsMap;
    }

    private function extractEntranceDataFromDoc(array $entranceDoc): array
    {
        return [
            Entrance\State::ENTRANCE_ID => $entranceDoc[Entrance\State::ENTRANCE_ID],
            Entrance\State::ADDRESS => $entranceDoc[Entrance\State::ADDRESS],
        ];
    }

    private function extractApartmentDataFromDoc(array $apartmentDoc): array
    {
        return [
            Apartment\State::APARTMENT_ID => $apartmentDoc[Apartment\State::APARTMENT_ID],
            Apartment\State::APARTMENT_NUMBER => $apartmentDoc[Apartment\State::APARTMENT_NUMBER],
        ];
    }
}
