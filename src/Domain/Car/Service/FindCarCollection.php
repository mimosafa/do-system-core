<?php

namespace DoSystem\Domain\Car\Service;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;

class FindCarCollection
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
     * @param array $params
     */
    public function handle(array $params = []): CarCollection
    {
        return $this->repository->find($params);
    }
}
