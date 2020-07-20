<?php

namespace DoSystem\Domain\Car\Service;

use DoSystem\Core\Domain\Car\CarRepositoryInterface;

class CarService
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * @var VinService
     */
    private $vinService;

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
     * Check vin string existence in datastore
     *
     * @param string $vin
     * @return bool
     */
    public function vinExists(string $vin): bool
    {
        if (!$this->vinService) {
            $this->vinService = new VinService($this->repository);
        }
        return $this->vinService->exists($vin);
    }
}
