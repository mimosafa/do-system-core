<?php

namespace DoSystem\Core\Domain\Car;

use DoSystem\Module\Domain\Model\AbstractValueObjectString;
use DoSystem\Module\Domain\Model\ValueObjectStringInterface;

final class CarValueName extends AbstractValueObjectString implements ValueObjectStringInterface
{
    /**
     * @var int
     */
    protected static $maxLength = 100;

    /**
     * @var bool
     */
    protected static $allowEmpty = true;

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return empty($this->value);
    }
}