<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement;

use FeeOffice\ContractManagement\Api;
use FeeOffice\ContractManagement\Infrastructure\System\EventMachineFactory;
use FeeOffice\ContractManagement\Infrastructure\System\MessageBoxFactory;
use FeeOffice\ContractManagement\Infrastructure\System\MessageSchemaMiddlewareFactory;
use Prooph\EventMachine\EventMachine;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

final class ConfigProvider
{
    const CONTRACT_EVENT_MACHINE = 'contract.event_machine';
    const CONTRACT_MESSAGE_BOX = 'contract.message_box';
    const CONTRACT_MESSAGE_SCHEMA_MIDDLEWARE = 'contract.message_schema_middleware';

    public function __invoke(): array
    {
        return [
            'contract' => $this->contractConfig(),
            'middleware_pipeline' => [
                [
                    // required:
                    'middleware' => BodyParamsMiddleware::class,
                    // optional:
                    'path'  => '/contract', // for path-segregated middleware
                    'priority' => 1,             // integer; to ensure specific order
                ]
            ],
            'routes' => [
                [
                    'path' => '/contract/messagebox',
                    'middleware' => self::CONTRACT_MESSAGE_BOX,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => '/contract/messagebox/{message_name:[A-Za-z0-9_.-\/]+}',
                    'middleware' => self::CONTRACT_MESSAGE_BOX,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => '/contract/messagebox-schema',
                    'middleware' => self::CONTRACT_MESSAGE_SCHEMA_MIDDLEWARE,
                    'allowed_methods' => ['GET'],
                ],
            ],
            'dependencies' => [
                'factories'  => [
                    self::CONTRACT_EVENT_MACHINE => EventMachineFactory::class,
                    self::CONTRACT_MESSAGE_BOX => MessageBoxFactory::class,
                    self::CONTRACT_MESSAGE_SCHEMA_MIDDLEWARE => MessageSchemaMiddlewareFactory::class,
                ],
            ]
        ];
    }

    private function contractConfig(): array
    {
        $env = getenv('PROOPH_ENV')?: 'prod';
        return [
            'env' => $env,
            'debug_mode' => $env === EventMachine::ENV_DEV,
            'pdo' => [
                'dsn' => getenv('PDO_DSN'),
                'user' => getenv('PDO_CONTRACT_USER'),
                'pwd' => getenv('PDO_CONTRACT_PWD'),
            ],
            'event_machine' => [
                'descriptions' => [
                    Api\Type::class,
                    Api\Command::class,
                    Api\Event::class,
                    Api\Query::class,
                    Api\Aggregate::class,
                    Api\Projection::class,
                    Api\Listener::class,
                ]
            ]
        ];
    }
}
