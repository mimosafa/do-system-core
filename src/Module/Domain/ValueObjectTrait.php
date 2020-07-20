<?php

namespace DoSystem\Core\Module\Domain;

trait ValueObjectTrait
{
    /**
     * @abstract
     *
     * @return mixed
     */
    abstract public function getValue();

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
    public static function of(...$value): ValueObjectInterface
    {
        if ($value[0] instanceof static) {
            return $value[0];
        }
        return new static(...$value);
    }
}
