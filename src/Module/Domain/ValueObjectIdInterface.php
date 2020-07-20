<?php

namespace DoSystem\Core\Module\Domain;

interface ValueObjectIdInterface extends ValueObjectInterface
{
    /**
     * @return bool
     */
    public function isPseudo(): bool;
}
