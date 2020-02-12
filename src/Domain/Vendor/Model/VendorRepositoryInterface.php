<?php

namespace DoSystem\Domain\Vendor\Model;

interface VendorRepositoryInterface
{
    /**
     * @param Vendor $model
     * @return VendorValueId
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function store(Vendor $model): VendorValueId;

    /**
     * @param VendorValueId $id
     * @return Vendor
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function findById(VendorValueId $id): Vendor;
}
