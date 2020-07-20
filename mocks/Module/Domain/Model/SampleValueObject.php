<?php

namespace DoSystemCoreMock\Module\Domain\Model;

use DoSystem\Core\Module\Domain\ValueObjectInterface;
use DoSystem\Core\Module\Domain\ValueObjectTrait;

class SampleValueObject implements ValueObjectInterface
{
    use ValueObjectTrait;

    private $value;

    public function __construct($value)
    {
        if ($value instanceof self) {
            $value = $value->getValue();
        }
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool
    {
        return $valueObject instanceof static
            && $this->getValue() === $valueObject->getValue()
            && \get_called_class() === \get_class($valueObject);
    }
}
