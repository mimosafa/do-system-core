<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\QueriedVendorOutputInterface;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueName;

class QueriedVendorOutputMock implements QueriedVendorOutputInterface
{
    /**
     * @var Vendor
     */
    private $model;

    /**
     * Constructor
     *
     * @param Vendor $model
     */
    public function __construct(Vendor $model)
    {
        $this->model = $model;
    }

    /**
     * @return VendorValueName
     */
    public function getName(): VendorValueName
    {
        return $this->model->getName();
    }
}
