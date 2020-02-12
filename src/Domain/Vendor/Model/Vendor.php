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
     * @param VendorValueId $id
     * @param VendorValueName $name
     * @param VendorValueStatus|null $status
     */
    public function __construct(VendorValueId $id, VendorValueName $name, ?VendorValueStatus $status = null)
    {
        $this->id = $id;
        $this->name = $name;
        if ($status === null) {
            $status = VendorValueStatus::defaultStatus();
        }
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
