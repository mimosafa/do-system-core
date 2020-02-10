<?php

namespace DoSystem\Domain\Vendor\Service;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;

class CreateEntity
{
    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $repository
     */
    public function __construct(VendorRepositoryInterface $repository)
    {
        $this->vendorRepository = $repository;
    }

    /**
     * @param string $name
     * @return Vendor
     */
    public function handle(string $name, int $statusEnum): Vendor
    {
        $nameVal = VendorValueName::of($name);
        $statusVal = VendorValueStatus::of($statusEnum);

        $model = new Vendor(null, $nameVal, $statusVal);
        $id = $this->vendorRepository->store($model);

        return $this->vendorRepository->findById($id);
    }
}
