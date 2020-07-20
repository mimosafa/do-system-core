<?php

namespace DoSystem\Core\Application\Vendor\Data;

use DoSystem\Core\Domain\Vendor\Vendor;

interface UpdateVendorOutputInterface
{
    /**
     * Constructor
     *
     * ** Note **
     * Parameter 1 & 2 must be named '$model' & '$modified'
     * @see DoSystem\Core\Application\Vendor\Service\UpdateVendorService::handle()
     *
     * @param Vendor $model
     * @param string[] $modified
     */
    public function __construct(Vendor $model, array $modified);
}
