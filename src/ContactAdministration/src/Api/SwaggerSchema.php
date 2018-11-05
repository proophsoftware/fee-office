<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use App\Util\Swagger;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

final class SwaggerSchema implements RequestHandlerInterface
{
    /**
     * @var array
     */
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var UriInterface $uri */
        $uri = $request->getAttribute('original_uri', $request->getUri());
        $serverUrl = $uri->withPath(str_replace('-schema', '', $uri->getPath()));
        $paths = [];

        foreach ($this->routes as $route) {
            $this->assertRoute($route);

            if(!method_exists($route['middleware'], 'schema')) continue;

            $operations = Swagger::fastRouteToOperations($route['path'], $route['allowed_methods'][0]);

            $pathSchema = call_user_func([$route['middleware'], 'schema']);

            if(!is_array($pathSchema)) {
                throw new \RuntimeException($route['middleware']."::schema() does not return a schema array.");
            }

            foreach ($operations as $operation) {
                foreach ($operation->parameters() as $parameter) {
                    $pathSchema['parameters'][] = [
                        Swagger::PARAM_IN => Swagger::PATH,
                        Swagger::PARAM_NAME => $parameter->name(),
                        Swagger::SCHEMA => Swagger::jsonSchemaToOpenApiSchema(
                            JsonSchema::string()->withPattern("^{$parameter->pattern()}$")->toArray()
                        )
                    ];
                }

                $paths[$operation->path()->toString()] = [
                    $operation->method()->toString() => $pathSchema
                ];
            }
        }

        $componentSchemas = [];
        foreach (Type::definitions() ?? [] as $componentName => $componentSchema) {
            $componentSchemas[$componentName] = Swagger::jsonSchemaToOpenApiSchema($componentSchema->toArray());
        }

        $schema = [
            'openapi' => '3.0.0',
            'servers' => [
                [
                    'description' => 'Contact Administration server',
                    'url' => (string)$serverUrl
                ]
            ],
            'info' => [
                'description' => 'An endpoint for sending messages to contact administration service.',
                'version' => '0.1.0',
                'title' => 'Contact Administration'
            ],
            'paths' => $paths,
            'components' =>  ['schemas' => $componentSchemas],
        ];

        return new JsonResponse($schema);
    }

    private function assertRoute(array $route): void
    {
        if(!array_key_exists('path', $route)) {
            throw new \InvalidArgumentException("Missing key path in expressive route: " . json_encode($route));
        }

        if(!array_key_exists('middleware', $route)) {
            throw new \InvalidArgumentException("Missing key middleware in expressive route: " . json_encode($route));
        }

        if(!array_key_exists('allowed_methods', $route)) {
            throw new \InvalidArgumentException("Missing key allowed_methods in expressive route: " . json_encode($route));
        }

        if(!class_exists($route['middleware'])) {
            throw new \InvalidArgumentException("Middleware in expressive route is not a valid class: " . json_encode($route));
        }

        if(!is_array($route['allowed_methods']) || count($route['allowed_methods']) !== 1) {
            throw new \InvalidArgumentException("allowed_method must only contain one http method (project convention): " . json_encode($route));
        }
    }
}
