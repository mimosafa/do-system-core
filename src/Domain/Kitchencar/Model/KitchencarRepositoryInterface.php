<?php

namespace DoSystem\Domain\Kitchencar\Model;

use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarCollection;

interface KitchencarRepositoryInterface
{
    /**
     * @param Kitchencar $model
     * @return KitchencarValueId
     */
    public function store(Kitchencar $model): KitchencarValueId;

    /**
     * @param KitchencarValueId $id
     * @return Kitchencar
     */
    public function findById(KitchencarValueId $id): Kitchencar;

    /**
     * @param Car $car
     * @param array $params
     * @return BrandCollection
     */
    public function findBrandsByCar(Car $car, array $params): BrandCollection;

    /**
     * @param Brand $brand
     * @param array $params
     * @return CarCollection
     */
    public function findCarsByBrand(Brand $brand, array $params): CarCollection:

    /**
     * @param array{
     *      @type int[]|null  $brand_id
     *      @type int[]|null  $car_id
     *      @type int[]|null  $vendor_id
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by
     *      @type string|null $order
     * } $params
     * @return KitchencarCollection
     */
    public function query(array $params): KitchencarCollection;
}
