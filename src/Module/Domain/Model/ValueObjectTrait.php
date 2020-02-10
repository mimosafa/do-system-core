<?php

namespace DoSystem\Module\Domain\Model;

trait ValueObjectTrait
{
    /**
     * @abstract
     *
     * @return mixed
     */
    abstract public function getValue();

    /**
     * @final
     *
     * @param ValueObjectInterface $valueObject
     * @return bool
     */
    final public function equals(ValueObjectInterface $valueObject): bool
    {
        return $valueObject instanceof static
            && $this->getValue() === $valueObject->getValue()
            && \get_called_class() === \get_class($valueObject);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @static
     *
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
