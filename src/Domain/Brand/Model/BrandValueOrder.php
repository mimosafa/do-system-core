<?php

namespace DoSystem\Domain\Brand\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

class BrandValueOrder implements ValueObjectInterface
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
