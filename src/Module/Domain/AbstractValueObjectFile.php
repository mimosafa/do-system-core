<?php

namespace DoSystem\Core\Module\Domain;

use SplFileInfo;

abstract class AbstractValueObjectFile extends SplFileInfo
{
    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->getPathname();
    }

    /**
     * @uses SplFileInfo::getRealPath()
     * @return bool
     */
    public function exists(): bool
    {
        return (bool) $this->getRealPath();
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

    /**
     * @param mixed $value
     * @return static
     */
    public static function of(...$value): ValueObjectInterface
    {
        $val = $value[0];
        return $val instanceof static ? $val : new static($val);
    }
}
