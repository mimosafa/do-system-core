<?php

namespace DoSystem\Module\Domain\Model;

interface ValueObjectIdInterface extends ValueObjectInterface
{
    /**
     * @return bool
     */
    public function isPseudo(): bool;
}
