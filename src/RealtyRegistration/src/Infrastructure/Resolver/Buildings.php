<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\Resolver;

use Prooph\EventMachine\Messaging\Message;
use Prooph\EventMachine\Persistence\DocumentStore;
use React\Promise\Deferred;
use FeeOffice\RealtyRegistration\Model\Building;

final class Buildings
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

    public function __invoke(Message $getBuildings, Deferred $deferred): void
    {
        $cursor = $this->documentStore->filterDocs($this->collectionName, new DocumentStore\Filter\AnyFilter());

        $buildingList = [];

        foreach ($cursor as $doc) {
            $buildingList[] = [
                Building\State::BUILDING_ID => $doc[Building\State::BUILDING_ID],
                Building\State::NAME => $doc[Building\State::NAME],
            ];
        }

        $deferred->resolve($buildingList);
    }
}
