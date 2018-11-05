<?php
declare(strict_types=1);

namespace App\Util\Swagger;

final class Path
{
    private $path;

    public static function fromFastRouteAndParameters(string $fastRoute, Parameter ...$parameters): self
    {
        foreach ($parameters as $parameter) {
            $fastRoute = str_replace("{{$parameter->name()}:{$parameter->pattern()}}", "{{$parameter->name()}}", $fastRoute);
        }

        return self::fromString($fastRoute);
    }

    public static function fromString(string $path): self
    {
        return new self($path);
    }

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public function toString(): string
    {
        return $this->path;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->path === $other->path;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
