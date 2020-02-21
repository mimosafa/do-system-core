<?php

namespace DoSystem\Module\Domain\Model;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface CollectionInterface extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback);
}
