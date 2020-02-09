<?php

namespace DoSystem\Domain\Models\Vendor;

use DoSystem\Module\Domain\Models\ValueObjectInterface;
use DoSystem\Module\Domain\Models\ValueObjectTrait;

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
