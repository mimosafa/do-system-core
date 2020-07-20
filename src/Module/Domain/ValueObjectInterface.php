<?php

namespace DoSystem\Core\Module\Domain;

interface ValueObjectInterface
{
    /**
     * @return mixed Scalar value of this value object
     */
    public function getValue();

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool;

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param mixed $value
     * @return self
     */
    public static function of(...$value): self;
}
