<?php

namespace DoSystem\Application\Kitchencar\Service;

use DoSystem\Application\Kitchencar\Data\UpdateKitchencarInputInterface;
use DoSystem\Application\Kitchencar\Data\UpdateKitchencarOutputInterface;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Kitchencar\Model\KitchencarRepositoryInterface;

class UpdateKitchencarService
{
    /**
     * @var KitchencarRepositoryInterface
     */
    private $repository;

    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepository;

    /**
     * @var CarRepositoryInterface
     */
    private $carRepositoryInterface;

    /**
     * Constructor
     *
     * @param KitchencarRepositoryInterface $repository
     * @param BrandRepositoryInterface $brandRepository
     * @param CarRepositoryInterface $carRepositoryInterface
     */
    public function __construct(KitchencarRepositoryInterface $repository, BrandRepositoryInterface $brandRepository, CarRepositoryInterface $carRepositoryInterface)
    {
        $this->repository = $repository;
        $this->brandRepository = $brandRepository;
        $this->carRepository = $carRepository;
    }

    /**
     * @param UpdateKitchencarInputInterface $filter
     * @return UpdateKitchencarOutputInterface
     */
    public function handle(QueryKitchencarFilterInterface $filter): UpdateKitchencarOutputInterface
    {
        //
    }
}
