<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\PreProcessor;

use FeeOffice\RealtyRegistration\Api\Command;
use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceExistsGuard;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\Common\Messaging\Message;
use Prooph\EventMachine\Commanding\CommandPreProcessor;

final class AddApartment implements CommandPreProcessor
{
    private $entranceExistsGuard;

    public function __construct(EntranceExistsGuard $entranceExistsGuard)
    {
        $this->entranceExistsGuard = $entranceExistsGuard;
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
        if(!$message->messageName() === Command::ADD_APARTMENT) {
            throw new \RuntimeException(__METHOD__  .' can only process ' . Command::ADD_APARTMENT . ' messages.', StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        $entranceId = $message->payload()[Payload::ENTRANCE_ID] ?? '';

        if(!$this->entranceExistsGuard->isKnownEntrance(EntranceId::fromString($entranceId))) {
            throw new \InvalidArgumentException("Entrance with id $entranceId does not exist.", StatusCodeInterface::STATUS_NOT_FOUND);
        }

        return $message;
    }
}
