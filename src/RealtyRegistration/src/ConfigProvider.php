<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration;

use FeeOffice\RealtyRegistration\Api;
use FeeOffice\RealtyRegistration\Infrastructure\System\EventMachineFactory;
use FeeOffice\RealtyRegistration\Infrastructure\System\MessageBoxFactory;
use FeeOffice\RealtyRegistration\Infrastructure\System\MessageSchemaMiddlewareFactory;
use Prooph\EventMachine\EventMachine;

final class ConfigProvider
{
    const REALTY_EVENT_MACHINE = 'realty.event_machine';
    const REALTY_MESSAGE_BOX = 'realty.message_box';
    const REALTY_MESSAGE_SCHEMA_MIDDLEWARE = 'realty.message_schema_middleware';

    public function __invoke(): array
    {
        return [
            'realty' => $this->realtyConfig(),
            'routes' => [
                [
                    'path' => '/realty/messagebox',
                    'middleware' => self::REALTY_MESSAGE_BOX,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => '/realty/messagebox/{message_name:[A-Za-z0-9_.-\/]+}',
                    'middleware' => self::REALTY_MESSAGE_BOX,
                    'allowed_methods' => ['POST'],
                ],
                [
                    'path' => '/realty/messagebox-schema',
                    'middleware' => self::REALTY_MESSAGE_SCHEMA_MIDDLEWARE,
                    'allowed_methods' => ['GET'],
                ],
            ],
            'dependencies' => [
                'factories'  => [
                    self::REALTY_EVENT_MACHINE => EventMachineFactory::class,
                    self::REALTY_MESSAGE_BOX => MessageBoxFactory::class,
                    self::REALTY_MESSAGE_SCHEMA_MIDDLEWARE => MessageSchemaMiddlewareFactory::class,
                ],
            ]
        ];
    }

    private function realtyConfig(): array
    {
        $env = getenv('PROOPH_ENV')?: 'prod';
        return [
            'env' => $env,
            'debug_mode' => $env === EventMachine::ENV_DEV,
            'pdo' => [
                'dsn' => getenv('PDO_DSN'),
                'user' => getenv('PDO_REALTY_USER'),
                'pwd' => getenv('PDO_REALTY_PWD'),
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
