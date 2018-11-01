<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Infrastructure\System;

use Codeliner\ArrayReader\ArrayReader;
use FeeOffice\RealtyRegistration\Api\Aggregate;
use FeeOffice\RealtyRegistration\Http\MessageSchemaMiddleware;
use App\Infrastructure\ServiceBus\CommandBus;
use App\Infrastructure\ServiceBus\ErrorHandler;
use App\Infrastructure\ServiceBus\EventBus;
use App\Infrastructure\ServiceBus\QueryBus;
use FeeOffice\RealtyRegistration\Infrastructure\ContextProvider;
use FeeOffice\RealtyRegistration\Infrastructure\Finder\ApartmentFinder;
use FeeOffice\RealtyRegistration\Infrastructure\Guard\AggregateExists;
use FeeOffice\RealtyRegistration\Infrastructure\PreProcessor;
use FeeOffice\RealtyRegistration\Infrastructure\Resolver;
use FeeOffice\RealtyRegistration\Model\Building\BuildingExistsGuard;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceExistsGuard;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\EventMachine\Container\ContainerChain;
use Prooph\EventMachine\Container\EventMachineContainer;
use Prooph\EventMachine\Container\ServiceRegistry;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\Http\MessageBox;
use Prooph\EventMachine\Persistence\DocumentStore;
use Prooph\EventMachine\Postgres\PostgresDocumentStore;
use Prooph\EventMachine\Projecting\AggregateProjector;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy;
use Prooph\EventStore\Pdo\PostgresEventStore;
use Prooph\EventStore\Pdo\Projection\PostgresProjectionManager;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\StreamName;
use Prooph\EventStore\TransactionalActionEventEmitterEventStore;
use Psr\Container\ContainerInterface;

final class ServiceFactory
{
    use ServiceRegistry;

    /**
     * @var ArrayReader
     */
    private $config;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(array $appConfig)
    {
        $this->config = new ArrayReader($appConfig);
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    //ContextProvider
    public function addApartmentContextProvider(): ContextProvider\AddApartment
    {
        return $this->makeSingleton(ContextProvider\AddApartment::class, function () {
            return new ContextProvider\AddApartment($this->apartmentFinder());
        });
    }

    //Command pre processor
    public function addEntrancePreProcessor(): PreProcessor\AddEntrance
    {
        return $this->makeSingleton(PreProcessor\AddEntrance::class, function () {
            return new PreProcessor\AddEntrance($this->buildingExistsGuard());
        });
    }

    public function addApartmentPreProcessor(): PreProcessor\AddApartment
    {
        return $this->makeSingleton(PreProcessor\AddApartment::class, function () {
            return new PreProcessor\AddApartment($this->entranceExistsGuard());
        });
    }

    //Guards
    public function entranceExistsGuard(): EntranceExistsGuard
    {
        return $this->aggregateExistsGuard();
    }

    public function buildingExistsGuard(): BuildingExistsGuard
    {
        return $this->aggregateExistsGuard();
    }

    public function aggregateExistsGuard(): AggregateExists
    {
        return $this->makeSingleton(AggregateExists::class, function () {
            return new AggregateExists($this->eventStore(), new StreamName($this->eventMachine()->writeModelStreamName()));
        });
    }

    //Resolvers
    public function buildingsResolver(): Resolver\Buildings
    {
        return $this->makeSingleton(Resolver\Buildings::class, function () {
            return new Resolver\Buildings(
                AggregateProjector::aggregateCollectionName(
                    $this->eventMachine()->appVersion(),
                    Aggregate::BUILDING
                ),
                $this->documentStore()
            );
        });
    }

    public function buildingResolver(): Resolver\Building
    {
        return $this->makeSingleton(Resolver\Building::class, function () {
            return new Resolver\Building(
                $this->documentStore(),
                AggregateProjector::aggregateCollectionName(
                    $this->eventMachine()->appVersion(),
                    Aggregate::BUILDING
                ),
                AggregateProjector::aggregateCollectionName(
                    $this->eventMachine()->appVersion(),
                    Aggregate::ENTRANCE
                ),
                AggregateProjector::aggregateCollectionName(
                    $this->eventMachine()->appVersion(),
                    Aggregate::APARTMENT
                )
            );
        });
    }

    public function apartmentAttributeLabelsResolver(): Resolver\ApartmentAttributeLabels
    {
        return $this->makeSingleton(Resolver\ApartmentAttributeLabels::class, function () {
            return new Resolver\ApartmentAttributeLabels();
        });
    }

    //Finders
    public function apartmentFinder(): ApartmentFinder
    {
        return $this->makeSingleton(ApartmentFinder::class, function () {
            return new ApartmentFinder(
                AggregateProjector::aggregateCollectionName(
                    $this->eventMachine()->appVersion(),
                    Aggregate::APARTMENT
                ),
                $this->documentStore()
            );
        });
    }

    //HTTP endpoints
    public function httpMessageBox(): MessageBox
    {
        return $this->makeSingleton(MessageBox::class, function () {
            return $this->eventMachine()->httpMessageBox();
        });
    }

    public function eventMachineHttpMessageSchema(): MessageSchemaMiddleware
    {
        return $this->makeSingleton(MessageSchemaMiddleware::class, function () {
            return new MessageSchemaMiddleware($this->eventMachine());
        });
    }

    public function healthCheckResolver(): HealthCheckResolver
    {
        return $this->makeSingleton(HealthCheckResolver::class, function () {
            return new HealthCheckResolver();
        });
    }

    public function pdoConnection(): \PDO
    {
        return $this->makeSingleton(\PDO::class, function () {
            $this->assertMandatoryConfigExists('pdo.dsn');
            $this->assertMandatoryConfigExists('pdo.user');
            $this->assertMandatoryConfigExists('pdo.pwd');
            return new \PDO(
                $this->config->stringValue('pdo.dsn'),
                $this->config->stringValue('pdo.user'),
                $this->config->stringValue('pdo.pwd')
            );
        });
    }

    protected function eventStorePersistenceStrategy(): PersistenceStrategy
    {
        return $this->makeSingleton(PersistenceStrategy::class, function () {
            return new PersistenceStrategy\PostgresSingleStreamStrategy();
        });
    }

    public function eventStore(): EventStore
    {
        return $this->makeSingleton(EventStore::class, function () {
            $eventStore = new PostgresEventStore(
                $this->eventMachine()->messageFactory(),
                $this->pdoConnection(),
                $this->eventStorePersistenceStrategy()
            );
            return new TransactionalActionEventEmitterEventStore(
                $eventStore,
                new ProophActionEventEmitter(TransactionalActionEventEmitterEventStore::ALL_EVENTS)
            );
        });
    }

    public function documentStore(): DocumentStore
    {
        return $this->makeSingleton(DocumentStore::class, function () {
            return new PostgresDocumentStore(
                $this->pdoConnection(),
                null,
                'CHAR(36) NOT NULL' //Use alternative docId schema, to allow uuids as well as md5 hashes
            );
        });
    }

    public function projectionManager(): ProjectionManager
    {
        return $this->makeSingleton(ProjectionManager::class, function () {
            return new PostgresProjectionManager(
                $this->eventStore(),
                $this->pdoConnection()
            );
        });
    }

    public function aggregateProjector(): AggregateProjector
    {
        return $this->makeSingleton(AggregateProjector::class, function () {
            return new AggregateProjector(
                $this->documentStore(),
                $this->eventMachine()
            );
        });
    }

    public function commandBus(): CommandBus
    {
        return $this->makeSingleton(CommandBus::class, function () {
            $commandBus = new CommandBus();
            $errorHandler = new ErrorHandler();
            $errorHandler->attachToMessageBus($commandBus);
            return $commandBus;
        });
    }

    public function eventBus(): EventBus
    {
        return $this->makeSingleton(EventBus::class, function () {
            $eventBus = new EventBus();
            $errorHandler = new ErrorHandler();
            $errorHandler->attachToMessageBus($eventBus);
            return $eventBus;
        });
    }

    public function queryBus(): QueryBus
    {
        return $this->makeSingleton(QueryBus::class, function () {
            $queryBus = new QueryBus();
            $errorHandler = new ErrorHandler();
            $errorHandler->attachToMessageBus($queryBus);
            return $queryBus;
        });
    }

    public function eventMachine(): EventMachine
    {
        $this->assertContainerIsset();
        return $this->makeSingleton(EventMachine::class, function () {
            //@TODO add config param to enable caching
            $eventMachine = new EventMachine();
            //Load descriptions here or add them to config/autoload/global.php
            foreach ($this->config->arrayValue('event_machine.descriptions') as $desc) {
                $eventMachine->load($desc);
            }
            $containerChain = new ContainerChain(
                $this->container,
                new EventMachineContainer($eventMachine)
            );
            $eventMachine->initialize($containerChain);
            return $eventMachine;
        });
    }

    private function assertContainerIsset(): void
    {
        if(null === $this->container) {
            throw new \RuntimeException("Main container is not set. Use " . __CLASS__ . "::setContainer() to set it.");
        }
    }

    private function assertMandatoryConfigExists(string $path): void
    {
        if(null === $this->config->mixedValue($path)) {
            throw  new \RuntimeException("Missing application config for $path");
        }
    }
}
