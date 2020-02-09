<?php

namespace DoSystem\Module\Domain\Models;

use DoSystem\Module\Domain\Models\ValueObjectInterface;

trait ValueObjectTrait
{
    /**
     * @return mixed
     */
    abstract public function getValue();

    /**
     * @param ValueObjectInterface $valueObject
     */
    public function equals(ValueObjectInterface $valueObject): bool
    {
        return $this->getValue() === $valueObject->getValue();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @param mixed $value
     * @return ValueObjectInterface
     */
    public static function of($value): ValueObjectInterface
    {
        if ($value instanceof static) {
            return $value;
        }
        return new static($value);
    }
}
