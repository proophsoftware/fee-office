<?php
declare(strict_types = 1);

chdir(dirname(__DIR__ . '/../../..'));

require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Prooph\EventMachine\EventMachine $eventMachine */
$eventMachine = $container->get(\FeeOffice\RealtyRegistration\ConfigProvider::REALTY_EVENT_MACHINE);

$eventMachine->bootstrap(getenv('PROOPH_ENV')?: 'prod', true);

/** @var \Prooph\EventStore\Projection\ProjectionManager $projectionManager */
$projectionManager = $container->get(\Prooph\EventMachine\EventMachine::SERVICE_ID_PROJECTION_MANAGER);

echo "Resetting " . \Prooph\EventMachine\Projecting\ProjectionRunner::eventMachineProjectionName($eventMachine->appVersion()) . "\n";

$projectionManager->resetProjection(\Prooph\EventMachine\Projecting\ProjectionRunner::eventMachineProjectionName($eventMachine->appVersion()));



