<?php

namespace DoSystem\Module\Domain\Model;

interface ValueObjectInterface
{
    /**
     * @return mixed Scalar value of this value object
     */
    public function getValue();

    /**
     * @param self $valueObject
     * @return bool
     */
    public function equals(self $valueObject): bool;

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
