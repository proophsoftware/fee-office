<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\ContextProvider;


use FeeOffice\RealtyRegistration\Api\Command;
use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Infrastructure\Finder\ApartmentFinder;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\EventMachine\Aggregate\ContextProvider;
use Prooph\EventMachine\Messaging\Message;

final class AddApartment implements ContextProvider
{
    /**
     * @var ApartmentFinder
     */
    private $apartmentFinder;

    public function __construct(ApartmentFinder $apartmentFinder)
    {
        $this->apartmentFinder = $apartmentFinder;
    }

    /**
     * @param Message $command
     * @return mixed The context passed as last argument to aggregate functions
     */
    public function provide(Message $command)
    {
        if(!$command->messageName() === Command::ADD_APARTMENT) {
            throw new \RuntimeException(__METHOD__  .' can only process ' . Command::ADD_APARTMENT . ' messages.', StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        $entranceId = EntranceId::fromString($command->payload()[Payload::ENTRANCE_ID]);

        return $this->apartmentFinder->getEntranceApartments($entranceId);
    }
}
