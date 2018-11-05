<?php
declare(strict_types=1);

namespace App\Util\Swagger;

final class Method
{
    public const GET = 'get';
    public const POST = 'post';
    public const PUT = 'put';
    public const PATCH = 'patch';
    public const DELETE = 'delete';
    public const HEAD = 'head';
    public const OPTIONS = 'options';

    private $method;

    private const allowedValues = [
        self::GET, self::POST, self::PUT, self::PATCH, self::DELETE, self::HEAD, self::OPTIONS,
    ];

    public static function fromString(string $method): self
    {
        return new self($method);
    }

    private function __construct(string $method)
    {
        if(!in_array($method, self::allowedValues)) {
            throw new \InvalidArgumentException(
                "Invalid mehtod given. Got $method, but can only be one of: " . implode(", ", self::allowedValues)
            );
        }

        $this->method = $method;
    }

    public function toString(): string
    {
        return $this->method;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->method === $other->method;
    }

    public function __toString(): string
    {
        return $this->method;
    }
}
