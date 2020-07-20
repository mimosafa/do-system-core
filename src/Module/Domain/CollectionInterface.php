<?php

namespace DoSystem\Core\Module\Domain;

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
     * @return bool
     */
    public function isEmpty();

    /**
     * @return bool
     */
    public function isNotEmpty();

    /**
     * @return int
     */
    public function count();

    /**
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback);
}
