<?php

namespace DoSystem\Module\Domain\Model;

interface ValueObjectFileInterface extends ValueObjectInterface
{
    /**
     * File size
     *
     * @return int
     */
    public function getSize();

    /**
     * Check existance of file
     *
     * @return bool
     */
    public function exists(): bool;
}
