<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Module\Domain\Model\ValueObjectEnumInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

final class VendorValueStatus implements ValueObjectEnumInterface
{
    use ValueObjectTrait;

    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
