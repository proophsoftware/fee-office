<?php
declare(strict_types = 1);

namespace FeeOffice\ContractManagement\Http;

use App\Util\Swagger;
use Prooph\Common\Messaging\Message;
use Prooph\EventMachine\EventMachine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

final class MessageSchemaMiddleware implements RequestHandlerInterface
{
    /**
     * @var EventMachine
     */
    private $eventMachine;

    public function __construct(EventMachine $eventMachine)
    {
        $this->eventMachine = $eventMachine;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var UriInterface $uri */
        $uri = $request->getAttribute('original_uri', $request->getUri());
        $serverUrl = $uri->withPath(str_replace('-schema', '', $uri->getPath()));
        $eventMachineSchema = $this->eventMachine->messageBoxSchema();
        $paths = [];
        foreach ($eventMachineSchema['properties']['commands'] as $messageName => $schema) {
            [$path, $pathDef] = $this->messageSchemaToPath($messageName, Message::TYPE_COMMAND, $schema);
            $paths[$path] = $pathDef;
        }
        foreach ($eventMachineSchema['properties']['events'] as $messageName => $schema) {
            [$path, $pathDef] = $this->messageSchemaToPath($messageName, Message::TYPE_EVENT, $schema);
            $paths[$path] = $pathDef;
        }
        foreach ($eventMachineSchema['properties']['queries'] as $messageName => $schema) {
            [$path, $pathDef] = $this->messageSchemaToPath($messageName, Message::TYPE_QUERY, $schema);
            $paths[$path] = $pathDef;
        }
        $componentSchemas = [];
        foreach ($eventMachineSchema['definitions'] ?? [] as $componentName => $componentSchema) {
            $componentSchemas[$componentName] = Swagger::jsonSchemaToOpenApiSchema($componentSchema);
        }
        $schema = [
            'openapi' => '3.0.0',
            'servers' => [
                [
                    'description' => 'Event Machine ' . $this->eventMachine->env() . ' server',
                    'url' => (string)$serverUrl
                ]
            ],
            'info' => [
                'description' => 'An endpoint for sending messages to contract management service.',
                'version' => $this->eventMachine->appVersion(),
                'title' => 'Contract Messagebox'
            ],
            'tags' => [
                [
                    'name' => 'queries',
                    'description' => 'Requests to read data from the system'
                ],
                [
                    'name' => 'commands',
                    'description' => 'Requests to write data to the system or execute an action',
                ],
                [
                    'name' => 'events',
                    'description' => 'Requests to add an event to the system'
                ]
            ],
            'paths' => $paths,
            'components' =>  ['schemas' => $componentSchemas],
        ];
        return new JsonResponse($schema);
    }

    private function messageSchemaToPath(string $messageName, string $messageType, array $messageSchema = null): array
    {
        $responses = [];
        if($messageType === Message::TYPE_QUERY) {
            $responses['200'] = [
                'description' => $messageSchema['response']['description'] ?? $messageName,
                'content' => [
                    'application/json' => [
                        'schema' => Swagger::jsonSchemaToOpenApiSchema($messageSchema['response'])
                    ]
                ]
            ];
            unset($messageSchema['response']);
        } else {
            $responses['202'] = [
                'description' => "$messageType accepted"
            ];
        }
        switch ($messageType) {
            case Message::TYPE_COMMAND:
                $tag = 'commands';
                break;
            case Message::TYPE_QUERY:
                $tag = 'queries';
                break;
            case Message::TYPE_EVENT:
                $tag = 'events';
                break;
            default:
                throw new \RuntimeException("Unknown message type given. Got $messageType");
        }
        return [
            "/{$messageName}",
            [
                'post' => [
                    'tags' => [$tag],
                    'summary' => $messageName,
                    'operationId' => "$messageType.$messageName",
                    'description' => $messageSchema['description'] ?? "Send a $messageName $messageType",
                    'requestBody' => [
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'payload' => Swagger::jsonSchemaToOpenApiSchema($messageSchema)
                                    ],
                                    'required' => ['payload']
                                ]
                            ]
                        ]
                    ],
                    'responses' => $responses
                ]
            ]
        ];
    }
}