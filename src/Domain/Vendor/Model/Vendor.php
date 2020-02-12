<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Vendor\Service\GetCarCollectionBelongsToVendor;

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
     * @param VendorValueStatus $status
     */
    public function __construct(VendorValueId $id, VendorValueName $name, VendorValueStatus $status)
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

    /**
     * @return CarCollection
     */
    public function getCars(): CarCollection
    {
        $service = resolve(GetCarCollectionBelongsToVendor::class);
        return $service->handle($this);
    }
}
