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
     * @param array $params
     * @return CarCollection
     */
    public function find(array $params): CarCollection;
}
