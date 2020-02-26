<?php

namespace DoSystem\Domain\Vendor\Service;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Vendor\Model\Vendor;

class GetCarsService
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
     * @param Vendor $model
     * @param array $params
     * @return CarCollection
     */
    public function handle(Vendor $model, array $params = []): CarCollection
    {
        $params['vendor_id'] = $model->getId()->getValue();
        return $this->repository->query($params);
    }
}
