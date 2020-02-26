<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Vendor\Service\GetCarsService;

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

    /**
     * @return VendorValueStatus
     */
    public function getStatus(): VendorValueStatus
    {
        return $this->status;
    }

    /**
     * @param array $params
     * @return CarCollection
     */
    public function getCars(array $params = []): CarCollection
    {
        $service = doSystem()->make(GetCarsService::class);
        return $service->handle($this, $params);
    }
}
