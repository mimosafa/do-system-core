<?php

namespace DoSystem\Module\Domain\Model;

trait ValueObjectTrait
{
    /**
     * @return mixed
     */
    abstract public function getValue();

    /**
     * @param ValueObjectInterface $valueObject
     * @return bool
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
