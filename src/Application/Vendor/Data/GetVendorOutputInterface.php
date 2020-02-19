<?php

namespace DoSystem\Application\Vendor\Data;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

interface GetVendorOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Vendor\Service\GetVendorService::handle(int $id)
     *
     * @param Vendor $model
     */
    public function __construct(Vendor $model);

    /**
     * @return VendorValueId
     */
    public function getId(): VendorValueId;

    /**
     * @return VendorValueName
     */
    public function getName(): VendorValueName;

    /**
     * @return VendorValueStatus
     */
    public function getStatus(): VendorValueStatus;
}
