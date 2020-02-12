<?php

namespace DoSystem\Module\Domain\Model;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface CollectionInterface extends ArrayAccess, Countable, IteratorAggregate
{
    //
}
