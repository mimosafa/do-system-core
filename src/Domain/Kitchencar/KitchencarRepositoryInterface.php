<?php

namespace DoSystem\Core\Domain\Kitchencar;

use DoSystem\Core\Domain\Brand\Brand;
use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Car\Car;
use DoSystem\Core\Domain\Car\CarCollection;

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
    public function findCarsByBrand(Brand $brand, array $params): CarCollection;

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
