<?php

declare(strict_types=1);

namespace FeeOfficeTest\RealtyRegistration;

use FeeOffice\RealtyRegistration\ConfigProvider;
use PHPUnit\Framework\TestCase;
use Prooph\EventMachine\Container\EventMachineContainer;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\Messaging\Message;

class BaseTestCase extends TestCase
{
    /**
     * @var EventMachine
     */
    protected $eventMachine;

    protected function setUp()
    {
        $this->eventMachine = new EventMachine();

        $config = (new ConfigProvider())();

        foreach ($config['realty']['event_machine']['descriptions'] as $description) {
            $this->eventMachine->load($description);
        }

        $this->eventMachine->initialize(new EventMachineContainer($this->eventMachine));
    }

    protected function tearDown()
    {
        $this->eventMachine = null;
    }

    protected function message(string $msgName, array $payload = [], array $metadata = []): Message
    {
        return $this->eventMachine->messageFactory()->createMessageFromArray($msgName, [
            'payload' => $payload,
            'metadata' => $metadata
        ]);
    }

    protected function assertRecordedEvent(string $eventName, array $payload, array $events, $assertNotRecorded = false): void
    {
        $isRecorded = false;

        foreach ($events as $evt) {
            if($evt === null) {
                continue;
            }

            [$evtName, $evtPayload] = $evt;

            if($eventName === $evtName) {
                $isRecorded = true;

                if(!$assertNotRecorded) {
                    $this->assertEquals($payload, $evtPayload, "Payload of recorded event $evtName does not match with expected payload.");
                }
            }
        }

        if($assertNotRecorded) {
            $this->assertFalse($isRecorded, "Event $eventName is recorded");
        } else {
            $this->assertTrue($isRecorded, "Event $eventName is not recorded");
        }
    }

    protected function assertNotRecordedEvent(string $eventName, array $events): void
    {
        $this->assertRecordedEvent($eventName, [], $events, true);
    }
}
