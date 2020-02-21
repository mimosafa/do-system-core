<?php

namespace DoSystem\Application\Vendor\Service;

use DoSystem\Application\Vendor\Data\CreateVendorInputInterface;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

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
