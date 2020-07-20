<?php

namespace DoSystem\Core\Application\Kitchencar\Service;

use DoSystem\Core\Application\Kitchencar\Data\CreateKitchencarInputInterface;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueId;
use DoSystem\Core\Domain\Kitchencar\Kitchencar;
use DoSystem\Core\Domain\Kitchencar\KitchencarRepositoryInterface;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueId;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueOrder;

class CreateKitchencarService
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
    private $carRepository;

    /**
     * Constructor
     *
     * @param KitchencarRepositoryInterface $repository
     * @param BrandRepositoryInterface $brandRepository
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(KitchencarRepositoryInterface $repository, BrandRepositoryInterface $brandRepository, CarRepositoryInterface $carRepository)
    {
        $this->repository = $repository;
        $this->brandRepository = $brandRepository;
        $this->carRepository = $carRepository;
    }

    /**
     * @param CreateKitchencarInputInterface $input
     * @return KitchencarValueId
     */
    public function handle(CreateKitchencarInputInterface $input): KitchencarValueId
    {
        $brand = $this->brandRepository->findById(BrandValueId::of($input->getBrandId()));
        $car = $this->carRepository->findById(CarValueId::of($input->getCarId()));
        $order = KitchencarValueOrder::of($input->getOrder());

        $id = KitchencarValueId::of(null);

        return $this->repository->store(new Kitchencar($id, $brand, $car, $order));
    }
}
