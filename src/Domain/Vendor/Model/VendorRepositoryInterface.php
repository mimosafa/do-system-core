<?php

namespace DoSystem\Domain\Vendor\Model;

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
    public function findById(VendorValueId $id): Vendor;
}
