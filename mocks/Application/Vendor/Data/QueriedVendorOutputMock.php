<?php

namespace DoSystemCoreMock\Application\Vendor\Data;

use DoSystem\Core\Application\Vendor\Data\QueriedVendorOutputInterface;
use DoSystem\Core\Domain\Vendor\Vendor;
use DoSystem\Core\Domain\Vendor\VendorValueName;

class QueriedVendorOutputMock implements QueriedVendorOutputInterface
{
    /**
     * @var Vendor
     */
    public $model;

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
