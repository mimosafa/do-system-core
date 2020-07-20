<?php

namespace DoSystem\Core\Application\Vendor\Data;

use DoSystem\Core\Domain\Vendor\Vendor;

interface QueriedVendorOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Core\Application\Vendor\Service\QueryVendorService::handle()
     *
     * @param Vendor $model
     */
    public function __construct(Vendor $model);
}
