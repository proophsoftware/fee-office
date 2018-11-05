<?php
declare(strict_types=1);

namespace App\Util\Swagger;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;
use FastRoute\BadRouteException;
use FastRoute\RouteParser\Std;

final class Operation implements ImmutableRecord
{
    use ImmutableRecordLogic;

    public const PATH = 'path';
    public const METHOD = 'method';
    public const PARAMETERS = 'parameters';

    /**
     * @var Parameter[]
     */
    private $parameters;
    
    /**
     * @var Method
     */
    private $method;
    
    /**
     * @var Path
     */
    private $path;

    /**
     * @param string $fastRoute
     * @param string $method
     * @return Operation[]
     */
    public static function fromFastRouteAndMethod(string $fastRoute, string $method): array
    {
        return self::determineOperations($fastRoute, $method);
    }

    private static function arrayPropItemTypeMap(): array
    {
        return [self::PARAMETERS => Parameter::class];
    }

    /**
     * @return Parameter[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return Method
     */
    public function method(): Method
    {
        return $this->method;
    }

    /**
     * @return Path
     */
    public function path(): Path
    {
        return $this->path;
    }

    private static function determineOperations(string $fastRoute, string $method): array
    {
        $operations = [];

        //Taken from FastRoute Std route parser, moved to own code to have more control over return structure
        $routeWithoutClosingOptionals = rtrim($fastRoute, ']');
        $numOptionals = strlen($fastRoute) - strlen($routeWithoutClosingOptionals);

        // Split on [ while skipping placeholders
        $segments = preg_split('~' . Std::VARIABLE_REGEX . '(*SKIP)(*F) | \[~x', $routeWithoutClosingOptionals);
        if ($numOptionals !== count($segments) - 1) {
            // If there are any ] in the middle of the route, throw a more specific error message
            if (preg_match('~' . Std::VARIABLE_REGEX . '(*SKIP)(*F) | \]~x', $routeWithoutClosingOptionals)) {
                throw new BadRouteException('Optional segments can only occur at the end of a route');
            }
            throw new BadRouteException("Number of opening '[' and closing ']' does not match");
        }

        $currentFastRoute = '';
        foreach ($segments as $n => $segment) {
            if ($segment === '' && $n !== 0) {
                throw new BadRouteException('Empty optional part');
            }

            $currentFastRoute .= $segment;
            $paramData = self::parsePlaceholders($currentFastRoute);
            $parameters = [];

            foreach ($paramData as $routePart) {
                if(is_string($routePart)) {
                    continue;
                }

                if(count($routePart) !== 2) {
                    throw new BadRouteException(
                        "Invalid param definition. Each route param must have a name and a pattern (project convention). Got "
                        . json_encode($routePart) . " in $segment");
                }

                $parameters[] = Parameter::fromArray([
                    Parameter::NAME => $routePart[0],
                    Parameter::PATTERN => $routePart[1]
                ]);
            }

            $operations[] = self::fromRecordData([
                self::PATH => Path::fromFastRouteAndParameters($currentFastRoute, ...$parameters),
                self::METHOD => Method::fromString(strtolower($method)),
                self::PARAMETERS => $parameters
            ]);
        }

        return $operations;
    }

    private static function parsePlaceholders($route): array
    {
        if (!preg_match_all(
            '~' . Std::VARIABLE_REGEX . '~x', $route, $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        )) {
            return [$route];
        }

        $offset = 0;
        $routeData = [];
        foreach ($matches as $set) {
            if ($set[0][1] > $offset) {
                $routeData[] = substr($route, $offset, $set[0][1] - $offset);
            }
            $routeData[] = [
                $set[1][0],
                isset($set[2]) ? trim($set[2][0]) : Std::DEFAULT_DISPATCH_REGEX
            ];
            $offset = $set[0][1] + strlen($set[0][0]);
        }

        if ($offset !== strlen($route)) {
            $routeData[] = substr($route, $offset);
        }

        return $routeData;
    }
}
