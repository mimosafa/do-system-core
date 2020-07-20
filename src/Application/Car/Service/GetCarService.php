<?php

namespace DoSystem\Core\Application\Car\Service;

use DoSystem\Core\Application\Car\Data\GetCarOutputInterface;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueId;

class GetCarService
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param CarRepositoryInterface $repository
     */
    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CarValueId $id
     * @return GetCarOutputInterface
     */
    public function handle(CarValueId $id): GetCarOutputInterface
    {
        $model = $this->repository->findById($id);
        return doSystem()->makeWith(GetCarOutputInterface::class, ['model' => $model]);
    }
}
