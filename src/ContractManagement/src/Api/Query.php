<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Api;

use FeeOffice\ContractManagement\Infrastructure\System\HealthCheckResolver;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;

class Query implements EventMachineDescription
{
    /**
     * Define query names using constants
     *
     * For a clean and simple API it is recommended to just use the name of the "thing"
     * you want to return as query name, see example for user queries:
     *
     * @example
     *
     * const USER = 'User';
     * const USERS = 'Users';
     * const FRIENDS = 'Friends';
     */

    /**
     * Default Query, used to perform health checks using the messagebox endpoint
     */
    const HEALTH_CHECK = Message::CTX.'HealthCheck';

    public static function describe(EventMachine $eventMachine): void
    {
        //Default query: can be used to check if service is up and running
        $eventMachine->registerQuery(self::HEALTH_CHECK) //<-- Payload schema is optional for queries
            ->resolveWith(HealthCheckResolver::class) //<-- Service id (usually FQCN) to get resolver from DI container
            ->setReturnType(Schema::healthCheck()); //<-- Type returned by resolver
    }
}
