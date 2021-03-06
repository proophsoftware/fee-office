<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Infrastructure\System;

use Codeliner\ArrayReader\ArrayReader;
use App\Infrastructure\ServiceBus\CommandBus;
use App\Infrastructure\ServiceBus\ErrorHandler;
use App\Infrastructure\ServiceBus\EventBus;
use App\Infrastructure\ServiceBus\QueryBus;
use FeeOffice\ContractManagement\Http\MessageSchemaMiddleware;
use FeeOffice\ContractManagement\Infrastructure\ContextProvider;
use FeeOffice\ContractManagement\Model\Contact\ContactAdministration;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\EventMachine\Container\ContainerChain;
use Prooph\EventMachine\Container\EventMachineContainer;
use Prooph\EventMachine\Container\ServiceNotFound;
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
    private $moduleContainer;

    /**
     * @var ContainerInterface
     */
    private $appContainer;

    public function __construct(array $appConfig, ContainerInterface $appContainer)
    {
        $this->config = new ArrayReader($appConfig);
        $this->appContainer = $appContainer;
    }

    public function setModuleContainer(ContainerInterface $container): void
    {
        $this->moduleContainer = $container;
    }

    //Module Proxy - provided by application layer through app container
    public function contactAdministration(): ContactAdministration
    {
        if(!$this->appContainer->has(ContactAdministration::class)) {
            throw ServiceNotFound::withServiceId(ContactAdministration::class);
        }

        return $this->appContainer->get(ContactAdministration::class);
    }

    //Context Provider
    public function addContractProvider(): ContextProvider\AddContract
    {
        return $this->makeSingleton(ContextProvider\AddContract::class, function () {
            return new ContextProvider\AddContract($this->contactAdministration());
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
                $this->moduleContainer,
                new EventMachineContainer($eventMachine)
            );
            $eventMachine->initialize($containerChain);
            return $eventMachine;
        });
    }

    private function assertContainerIsset(): void
    {
        if(null === $this->moduleContainer) {
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
