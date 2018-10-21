<?php
declare(strict_types=1);

namespace FeeOfficeTest\RealtyRegistration\Infrastructure\Guard;

use FeeOffice\RealtyRegistration\Api\Aggregate;
use FeeOffice\RealtyRegistration\Api\Event;
use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Infrastructure\Guard\AggregateExists;
use FeeOfficeTest\RealtyRegistration\BaseTestCase;
use Prooph\EventMachine\EventMachine;
use Prooph\EventStore\StreamName;

class AggregateExistsTest extends BaseTestCase
{
    const BUILDING_ID = '4436b5cd-4c6a-4bf2-b258-cc9e045d2361';

    /**
     * @var AggregateExists
     */
    private $guard;

    protected function setUp()
    {
        parent::setUp();

        $container = $this->eventMachine->bootstrapInTestMode([
            $this->message(Event::BUILDING_REGISTERED, [
                Payload::BUILDING_ID => self::BUILDING_ID,
                Payload::NAME => 'Test Building'
            ])
        ]);

        $eventStore = $container->get(EventMachine::SERVICE_ID_EVENT_STORE);

        $this->guard = new AggregateExists($eventStore, new StreamName($this->eventMachine->writeModelStreamName()));
    }

    /**
     * @test
     */
    public function it_checks_that_aggregate_exists()
    {
        $this->assertTrue($this->guard->isKnownAggregate(Aggregate::BUILDING, self::BUILDING_ID));
        $this->assertFalse($this->guard->isKnownAggregate(Aggregate::ENTRANCE, self::BUILDING_ID));
    }
}
