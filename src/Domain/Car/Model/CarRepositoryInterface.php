<?php

namespace DoSystem\Domain\Car\Model;

interface CarRepositoryInterface
{
    /**
     * @param Car $model
     * @return CarValueId
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function store(Car $model): CarValueId;

    /**
     * @param CarValueId $id
     * @return Car
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function findById(CarValueId $id): Car;

    /**
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $vin
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by  'status'|'order'
     *      @type string|null $order  'ASC'|'DESC'
     * } $params
     * @return CarCollection
     */
    public function query(array $params): CarCollection;
}
