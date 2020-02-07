<?php

namespace DoSystem\Domain\Models\Vendor;

class Vendor
{
    /**
     * @var VendorValueId
     */
    private $id;

    /**
     * @var VendorValueName
     */
    private $name;

    /**
     * Constructor
     *
     * @param VendorValueId $id
     * @param VendorValueName $name
     */
    public function __construct(VendorValueId $id, VendorValueName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return VendorValueId
     */
    public function getId(): VendorValueId
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
