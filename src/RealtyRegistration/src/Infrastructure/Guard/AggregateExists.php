<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\Guard;

use FeeOffice\RealtyRegistration\Api\Aggregate;
use FeeOffice\RealtyRegistration\Model\Building\BuildingExistsGuard;
use FeeOffice\RealtyRegistration\Model\Building\BuildingId;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceExistsGuard;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;
use Prooph\EventStore\StreamName;

final class AggregateExists implements BuildingExistsGuard, EntranceExistsGuard
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var StreamName
     */
    private $writeeModelStream;

    public function __construct(EventStore $eventStore, StreamName $writeModelStream)
    {
        $this->eventStore = $eventStore;
        $this->writeeModelStream = $writeModelStream;
    }

    public function isKnownAggregate(string $aggregateType, string $aggregateId): bool
    {
        $stream = $this->eventStore->load(
            $this->writeeModelStream,
            1,
            1,
            (new MetadataMatcher())->withMetadataMatch(
                '_aggregate_id',
                Operator::EQUALS(),
                $aggregateId
            )->withMetadataMatch(
                '_aggregate_type',
                Operator::EQUALS(),
                $aggregateType
            )
        );

        return $stream->valid();
    }

    public function isKnownBuilding(BuildingId $buildingId): bool
    {
        return $this->isKnownAggregate(Aggregate::BUILDING, $buildingId->toString());
    }

    public function isKnownEntrance(EntranceId $entranceId): bool
    {
        return $this->isKnownAggregate(Aggregate::ENTRANCE, $entranceId->toString());
    }
}
