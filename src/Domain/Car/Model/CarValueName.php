<?php

namespace DoSystem\Domain\Car\Model;

use DoSystem\Module\Domain\Model\AbstractValueObjectString;
use DoSystem\Module\Domain\Model\ValueObjectStringInterface;

final class CarValueName extends AbstractValueObjectString implements ValueObjectStringInterface
{
    /**
     * @var int
     */
    protected $maxLength = 100;

    /**
     * @var bool
     */
    protected $allowEmpty = true;

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return empty($this->value);
    }
}
