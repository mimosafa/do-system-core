<?php

namespace DoSystem\Core\Domain\Car;

use DoSystem\Core\Module\Domain\ValueObjectInterface;
use DoSystem\Core\Module\Domain\ValueObjectTrait;

class CarValueOrder implements ValueObjectInterface
{
    use ValueObjectTrait;

    /**
     * @var int|null
     */
    private $value;

    /**
     * Constructor
     *
     * @param int|null $value
     */
    public function __construct(?int $value)
    {
        if (!self::isValid($value)) {
            throw new \Exception();
        }
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function equals($value): bool
    {
        return $value instanceof self
            && $this->getValue() === $value->getValue();
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool
    {
        if ($value === null) {
            return true;
        }
        if (!is_int($value)) {
            return false;
        }
        return $value > -1;
    }
}
