<?php

namespace DoSystem\Core\Domain\Prefecture;

use DoSystem\Core\Module\Domain\ValueObjectInterface;
use DoSystem\Core\Module\Domain\ValueObjectTrait;

final class PrefectureValueId implements ValueObjectInterface
{
    use ValueObjectTrait;

    /**
     * @var int
     */
    private $value;

    /**
     * @var int
     */
    private static $min = 1;
    private static $max = 47;

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct(int $value)
    {
        if ($value < self::$min OR $value > self::$max) {
            throw new \Exception();
        }
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool
    {
        return $valueObject instanceof self
            && $this->getValue() === $valueObject->getValue();
    }
}
