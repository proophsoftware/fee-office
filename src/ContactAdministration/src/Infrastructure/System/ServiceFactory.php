<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Infrastructure\System;

use Codeliner\ArrayReader\ArrayReader;
use FeeOffice\ContactAdministration\Api\AddCompany;
use FeeOffice\ContactAdministration\Api\AddPerson;
use FeeOffice\ContactAdministration\Api\ContactCardByNameSearch;
use FeeOffice\ContactAdministration\Api\GetContactCard;
use FeeOffice\ContactAdministration\Infrastructure\Persistence\ContactCardDocumentStore;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Prooph\EventMachine\Container\ServiceRegistry;
use Prooph\EventMachine\Persistence\DocumentStore;
use Prooph\EventMachine\Postgres\PostgresDocumentStore;
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

    //API
    public function addPerson(): AddPerson
    {
        return $this->makeSingleton(AddPerson::class, function () {
            return new AddPerson($this->contactCardCollection(), $this->resourceUriFactory());
        });
    }

    public function addCompany(): AddCompany
    {
        return $this->makeSingleton(AddCompany::class, function () {
            return new AddCompany($this->contactCardCollection(), $this->resourceUriFactory());
        });
    }

    public function getContactCard(): GetContactCard
    {
        return $this->makeSingleton(GetContactCard::class, function () {
            return new GetContactCard($this->contactCardCollection());
        });
    }

    public function contactCardByNameSearch(): ContactCardByNameSearch
    {
        return $this->makeSingleton(ContactCardByNameSearch::class, function () {
            return new ContactCardByNameSearch($this->contactCardCollection());
        });
    }

    //Http
    public function resourceUriFactory(): ResourceUriFactory
    {
        return $this->makeSingleton(ResourceUriFactory::class, function () {
            return new ResourceUriFactory();
        });
    }

    //Persistence
    public function contactCardCollection(): ContactCardCollection
    {
        return $this->makeSingleton(ContactCardCollection::class, function () {
            return new ContactCardDocumentStore($this->documentStore());
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

    public function documentStore(): DocumentStore
    {
        return $this->makeSingleton(DocumentStore::class, function () {
            return new PostgresDocumentStore(
                $this->pdoConnection(),
                ''
            );
        });
    }

    private function assertMandatoryConfigExists(string $path): void
    {
        if(null === $this->config->mixedValue($path)) {
            throw  new \RuntimeException("Missing application config for $path");
        }
    }
}
