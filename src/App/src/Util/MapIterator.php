<?php
declare(strict_types=1);

namespace App\Util;

use Traversable;

final class MapIterator extends \IteratorIterator
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(Traversable $iterator, callable $callback)
    {
        parent::__construct($iterator);
        $this->callback = $callback;
    }

    public function current()
    {
        return call_user_func($this->callback, parent::current());
    }
}
