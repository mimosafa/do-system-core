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
     * @var VendorValueStatus
     */
    private $status;

    /**
     * Constructor
     *
     * @param VendorValueId|null $id
     * @param VendorValueName $name
     */
    public function __construct(?VendorValueId $id, VendorValueName $name, VendorValueStatus $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
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

    /**
     * @return VendorValueStatus
     */
    public function getStatus(): VendorValueStatus
    {
        return $this->status;
    }
}
