<?php

namespace DoSystem\Core\Module\Domain;

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
