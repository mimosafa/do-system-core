<?php

namespace DoSystem\Domain\Models\Vendor;

use DoSystem\Module\Domain\Models\ValueObjectInterface;
use DoSystem\Module\Domain\Models\ValueObjectTrait;

final class VendorValueName implements ValueObjectInterface
{
    use ValueObjectTrait;

    /**
     * @var string
     */
    private $value;

    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
