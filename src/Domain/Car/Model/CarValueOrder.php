<?php

namespace DoSystem\Domain\Car\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

class CarValueOrder implements ValueObjectInterface
{
    use ValueObjectTrait;

    /**
     * @var int
     */
    private $value;

    /**
     * Constructor
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    public function equals($value): bool
    {
        return $value instanceof self
            && $this->getValue() === $value->getValue();
    }
}
