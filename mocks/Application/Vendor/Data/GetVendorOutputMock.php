<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\GetVendorOutputInterface;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

class GetVendorOutputMock implements GetVendorOutputInterface
{
    /**
     * @var VendorValueId
     */
    public $id;

    /**
     * @var VendorValueName;
     */
    public $name;

    /**
     * @var VendorValueStatus
     */
    public $status;

    /**
     * Constructor
     *
     * @param Vendor $model
     */
    public function __construct(Vendor $model)
    {
        $this->id = $model->getId();
        $this->name = $model->getName();
        $this->status = $model->getStatus();
    }

    public function getId(): VendorValueId
    {
        return $this->id;
    }

    public function getName(): VendorValueName
    {
        return $this->name;
    }

    public function getStatus(): VendorValueStatus
    {
        return $this->status;
    }
}
