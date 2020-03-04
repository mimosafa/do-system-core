<?php

namespace DoSystem\Domain\Brand\Model;

interface BrandRepositoryInterface
{
    /**
     * @param Brand $model
     * @return BrandValueId
     */
    public function store(Brand $model): BrandValueId;

    /**
     * @param BrandValueId $id
     * @return Brand
     */
    public function findById(BrandValueId $id): Brand;

    /**
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $name
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     * } $params
     * @return BrandCollection
     */
    public function query(array $params): BrandCollection;
}
