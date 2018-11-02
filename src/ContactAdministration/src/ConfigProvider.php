<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration;

use FeeOffice\ContactAdministration\Api\AddCompany;
use FeeOffice\ContactAdministration\Api\AddPerson;
use FeeOffice\ContactAdministration\Api\ContactCardByNameSearch;
use FeeOffice\ContactAdministration\Api\GetContactCard;
use FeeOffice\ContactAdministration\Infrastructure\System\AbstractServiceFactory;
use FeeOffice\ContactAdministration\Infrastructure\System\ContactContextContainerFactory;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\JsonSchema\Type\UuidType;
use Ramsey\Uuid\Uuid;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

final class ConfigProvider
{
    public const SERVICE_CONTACT_CONTAINER = 'contact.container';
    public const CONTACT_BASE_URL = '/contact';
    public const URI_PATH_PERSON = '/person';
    public const URI_PATH_COMPANY = '/company';
    public const URI_PATH_CONTACT_CARD = '/contact-card';
    private const VALID_UUID_PATTERN = '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}';

    public function __invoke(): array
    {
        return [
            'contact' => $this->contactConfig(),
            'middleware_pipeline' => [
                [
                    // required:
                    'middleware' => BodyParamsMiddleware::class,
                    // optional:
                    'path'  => self::CONTACT_BASE_URL, // for path-segregated middleware
                    'priority' => 1,             // integer; to ensure specific order
                ]
            ],
            'routes' => [
                [
                    'path' => self::CONTACT_BASE_URL . self::URI_PATH_PERSON,
                    'middleware' => AddPerson::class,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => self::CONTACT_BASE_URL . self::URI_PATH_COMPANY,
                    'middleware' => AddCompany::class,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => self::CONTACT_BASE_URL . self::URI_PATH_CONTACT_CARD . '/{id:'.self::VALID_UUID_PATTERN.'}',
                    'middleware' => GetContactCard::class,
                    'allowed_methods' => ['GET'],
                ],
                [
                    'path' => self::CONTACT_BASE_URL . self::URI_PATH_CONTACT_CARD,
                    'middleware' => ContactCardByNameSearch::class,
                    'allowed_methods' => ['GET'],
                ]
//                [
//                    'path' => '/contract/messagebox-schema',
//                    'middleware' => self::CONTRACT_MESSAGE_SCHEMA_MIDDLEWARE,
//                    'allowed_methods' => ['GET'],
//                ],
            ],
            'dependencies' => [
                'factories'  => [
                    self::SERVICE_CONTACT_CONTAINER => ContactContextContainerFactory::class,
                ],
                'abstract_factories' => [
                    AbstractServiceFactory::class,
                ],
            ]
        ];
    }

    private function contactConfig(): array
    {
        $env = getenv('PROOPH_ENV')?: 'prod';
        return [
            'env' => $env,
            'debug_mode' => $env === EventMachine::ENV_DEV,
            'pdo' => [
                'dsn' => getenv('PDO_DSN'),
                'user' => getenv('PDO_CONTACT_USER'),
                'pwd' => getenv('PDO_CONTACT_PWD'),
            ],
        ];
    }
}
