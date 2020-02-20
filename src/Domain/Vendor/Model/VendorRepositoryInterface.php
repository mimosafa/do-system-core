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

    /**
     * @param array{
     *      @type string|null $name
     *      @type int[]|null  $status
     * } $params
     * @return VendorCollection
     */
    public function query(array $params): VendorCollection;
}
