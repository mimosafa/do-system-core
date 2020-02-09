<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

final class VendorValueId implements ValueObjectInterface
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
}
