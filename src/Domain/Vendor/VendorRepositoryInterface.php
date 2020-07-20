<?php

namespace DoSystem\Core\Domain\Vendor;

interface VendorRepositoryInterface
{
    /**
     * @param Vendor $model
     * @return VendorValueId
     * @throws \DoSystem\Core\Exception\NotFoundException
     */
    public function store(Vendor $model): VendorValueId;

    /**
     * @param VendorValueId $id
     * @return Vendor
     * @throws \DoSystem\Core\Exception\NotFoundException
     */
    public function findById(VendorValueId $id): Vendor;

    /**
     * @param array{
     *      @type string|null $name
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by  'id'|'name'|'status'
     *      @type string|null $order  'ASC'|'DESC'
     * } $params
     * @return VendorCollection
     */
    public function query(array $params): VendorCollection;
}
