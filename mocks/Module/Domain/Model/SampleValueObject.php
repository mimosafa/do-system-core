<?php

namespace DoSystemMock\Module\Domain\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

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
