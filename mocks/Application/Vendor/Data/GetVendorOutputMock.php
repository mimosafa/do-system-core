<?php

namespace DoSystemCoreMock\Application\Vendor\Data;

use DoSystem\Core\Application\Vendor\Data\GetVendorOutputInterface;
use DoSystem\Core\Domain\Vendor\Vendor;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorValueName;
use DoSystem\Core\Domain\Vendor\VendorValueStatus;

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
