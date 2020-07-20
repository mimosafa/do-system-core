<?php

namespace DoSystem\Core\Domain\Brand;

interface BrandRepositoryInterface
{
    /**
     * @param Brand $model
     * @return BrandValueId
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function store(Brand $model): BrandValueId;

    /**
     * @param BrandValueId $id
     * @return Brand
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function findById(BrandValueId $id): Brand;

    /**
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $name
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by  'name'|'status'|'order'
     *      @type string|null $order  'ASC'|'DESC'
     * } $params
     * @return BrandCollection
     */
    public function query(array $params): BrandCollection;
}
