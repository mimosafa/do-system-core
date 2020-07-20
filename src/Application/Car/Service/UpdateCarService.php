<?php

namespace DoSystem\Core\Application\Car\Service;

use DoSystem\Core\Application\Car\Data\UpdateCarInputInterface;
use DoSystem\Core\Application\Car\Data\UpdateCarOutputInterface;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueId;
use DoSystem\Core\Domain\Car\CarValueName;
use DoSystem\Core\Domain\Car\CarValueStatus;
use DoSystem\Core\Domain\Car\CarValueVin;
use DoSystem\Domain\Car\Service\CarService;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;

class UpdateCarService
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * @var CarService
     */
    private $service;

    /**
     * Constructor
     *
     * @param CarRepositoryInterface $repository
     */
    public function __construct(CarRepositoryInterface $repository, CarService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * @param UpdateCarInputInterface $input
     * @return UpdateCarOutputInterface
     */
    public function handle(UpdateCarInputInterface $input): UpdateCarOutputInterface
    {
        $model = $this->repository->findById(CarValueId::of($input->getId()));

        $vinValue = $input->getVin();
        $statusValue = $input->getStatus();
        $nameValue = $input->getName();

        /** @var array */
        $modified = [];

        if ($vinValue !== null) {
            if (CarValueVin::isValid($vinValue) && !$this->service->vinExists($vinValue)) {
                $modified[] = $model->setVin(CarValueVin::of($vinValue)) ? CarValueVin::class : '';
            }
        }

        if ($statusValue !== null) {
            if (CarValueStatus::isValid($statusValue)) {
                $modified[] = $model->setStatus(CarValueStatus::of($statusValue)) ? CarValueStatus::class : '';
            }
        }

        if ($nameValue !== null) {
            if (CarValueName::isValid($nameValue)) {
                $modified[] = $model->setName(CarValueName::of($nameValue)) ? CarValueName::class : '';
            }
        }

        $id = $this->repository->store($model);

        return doSystem()->makeWith(UpdateCarOutputInterface::class, [
            'model' => $this->repository->findById($id),
            'modified' => \array_filter($modified),
        ]);
    }
}
