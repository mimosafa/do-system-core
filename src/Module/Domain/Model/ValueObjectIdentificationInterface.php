<?php

namespace DoSystem\Module\Domain\Model;

interface ValueObjectIdentificationInterface extends ValueObjectInterface
{
    /**
     * @return bool
     */
    public function exists(): bool;
}
