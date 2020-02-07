<?php

namespace DoSystem\Domain\Repositories;

use DoSystem\Domain\Models\Vendor\Vendor;
use DoSystem\Domain\Models\Vendor\VendorValueId;

interface VendorRepositoryInterface
{
    /**
     * @param Vendor $model
     * @return VendorValueId
     */
    public function store(Vendor $model): VendorValueId;

    /**
     * @param VendorValueId $id
     * @return Vendor
     */
    public function find(VandorValueId $id): Vendor;
}
