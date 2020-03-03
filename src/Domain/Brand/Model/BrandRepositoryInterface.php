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
}
