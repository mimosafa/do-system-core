<?php

namespace DoSystem\Module\Domain\Model;

interface ValueObjectStringInterface extends ValueObjectInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool;
}
