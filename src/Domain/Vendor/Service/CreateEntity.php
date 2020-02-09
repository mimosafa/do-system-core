<?php

namespace DoSystem\Domain\Vendor\Service;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueName;
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
    public function handle(string $name): Vendor
    {
        $nameVal = new VendorValueName($name);
        $id = $this->vendorRepository->store(new Vendor(null, $nameVal));

        return $this->vendorRepository->findById($id);
    }
}
