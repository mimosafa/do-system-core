<?php

namespace DoSystem\Domain\Models\Vendor;

final class VendorValueName
{
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
