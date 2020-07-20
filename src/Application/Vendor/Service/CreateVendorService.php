<?php

namespace DoSystem\Core\Application\Vendor\Service;

use DoSystem\Core\Application\Vendor\Data\CreateVendorInputInterface;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\Vendor;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorValueName;
use DoSystem\Core\Domain\Vendor\VendorValueStatus;

class CreateVendorService
{
    /**
     * @var VendorRepositoryService
     */
    private $repository;

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $repository
     */
    public function __construct(VendorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateVendorInputInterface $input
     * @return VendorValueId
     */
    public function handle(CreateVendorInputInterface $input): VendorValueId
    {
        $id = VendorValueId::of(null); // Pseudo Id for creating
        
        $name = VendorValueName::of($input->getName());

        $status = $input->getStatus();

        // If not set $status, pass default Status
        $status = isset($status) ? VendorValueStatus::of($status) : VendorValueStatus::default();

        return $this->repository->store(new Vendor($id, $name, $status));
    }
}
