<?php

namespace DoSystem\Application\Kitchencar\Service;

use DoSystem\Application\Kitchencar\Data\CreateKitchencarInputInterface;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Kitchencar\Model\Kitchencar;
use DoSystem\Domain\Kitchencar\Model\KitchencarRepositoryInterface;
use DoSystem\Domain\Kitchencar\Model\KitchencarValueId;
use DoSystem\Domain\Kitchencar\Model\KitchencarValueOrder;

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
