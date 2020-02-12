<?php

namespace DoSystem\Domain\Car\Model;

interface CarRepositoryInterface
{
    /**
     * @param Car $model
     * @return CarValueId
     */
    public function store(Car $model): CarValueId;

    /**
     * @param CarValueId $id
     * @return Car
     */
    public function findById(CarValueId $id): Car;

    /**
     * @param CarValueVin $vin
     * @return Car
     */
    public function findByVin(CarValueVin $vin): Car;

    /**
     * @param array $params
     * @return CarCollection
     */
    public function find(array $params): CarCollection;
}
