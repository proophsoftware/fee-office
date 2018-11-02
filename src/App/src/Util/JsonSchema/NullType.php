<?php
declare(strict_types=1);

namespace App\Util\JsonSchema;

use Prooph\EventMachine\JsonSchema\AnnotatedType;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type;
use Prooph\EventMachine\JsonSchema\Type\HasAnnotations;

final class NullType implements AnnotatedType
{
    use HasAnnotations;

    public function toArray(): array
    {
        return array_merge(['type' => JsonSchema::TYPE_NULL], $this->annotations());
    }

    public function asNullable(): Type
    {
        return $this;
    }
}
