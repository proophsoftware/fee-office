<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\PreProcessor;

use FeeOffice\RealtyRegistration\Api\Command;
use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Building\BuildingExistsGuard;
use FeeOffice\RealtyRegistration\Model\Building\BuildingId;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\Common\Messaging\Message;
use Prooph\EventMachine\Commanding\CommandPreProcessor;

final class AddEntrance implements CommandPreProcessor
{
    /**
     * @var BuildingExistsGuard
     */
    private $buildingExistsGuard;

    public function __construct(BuildingExistsGuard $buildingExistsGuard)
    {
        $this->buildingExistsGuard = $buildingExistsGuard;
    }

    /**
     * Message will be of type Message::TYPE_COMMAND
     *
     * A PreProcessor can change the message and return the changed version (messages are immutable).
     *
     * @param Message $message
     * @return Message
     */
    public function preProcess(Message $message): Message
    {
        if(!$message->messageName() === Command::ADD_ENTRANCE) {
            throw new \RuntimeException(__METHOD__  .' can only process ' . Command::ADD_ENTRANCE . ' messages.', StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        $buildingId = $message->payload()[Payload::BUILDING_ID] ?? '';

        if(!$this->buildingExistsGuard->isKnownBuilding(BuildingId::fromString($buildingId))) {
            throw new \InvalidArgumentException("Building with id $buildingId does not exist.", StatusCodeInterface::STATUS_NOT_FOUND);
        }

        return $message;
    }
}
