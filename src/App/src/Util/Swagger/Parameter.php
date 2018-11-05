<?php
declare(strict_types=1);

namespace App\Util\Swagger;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class Parameter implements ImmutableRecord
{
    use ImmutableRecordLogic;

    public const NAME = 'name';
    public const PATTERN = 'pattern';

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
