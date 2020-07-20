<?php

namespace DoSystem\Core\Application\Vendor\Data;

use DoSystem\Core\Domain\Vendor\Vendor;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorValueName;
use DoSystem\Core\Domain\Vendor\VendorValueStatus;

interface GetVendorOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Core\Application\Vendor\Service\GetVendorService::handle(int $id)
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
