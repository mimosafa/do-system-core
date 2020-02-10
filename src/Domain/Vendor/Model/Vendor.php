<?php

namespace DoSystem\Domain\Vendor\Model;

class Vendor
{
    /**
     * @var VendorValueId|null
     */
    private $id;

    /**
     * @var VendorValueName
     */
    private $name;

    /**
     * Constructor
     *
     * @param VendorValueId|null $id
     * @param VendorValueName $name
     */
    public function __construct(?VendorValueId $id, VendorValueName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return VendorValueId|null
     */
    public function getId(): ?VendorValueId
    {
        return $this->id;
    }

    /**
     * @return VendorValueName
     */
    public function getName(): VendorValueName
    {
        return $this->name;
    }
}