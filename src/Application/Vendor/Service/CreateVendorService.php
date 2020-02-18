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
     * @param CreateVendorInputInterface $data
     * @return VendorValueId
     */
    public function handle(CreateVendorInputInterface $data): VendorValueId
    {
        // Pseudo Id for creating
        $id = VendorValueId::of(null);

        $name = $data->getName();
        $status = $data->getStatus();

        if (!$name) {
            // $name is required
            throw new \Exception();
        }
        $name = VendorValueName::of($name);

        // If not set $status, pass default Status
        $status = isset($status) ? VendorValueStatus::of($status) : VendorValueStatus::defaultStatus();

        return $this->repository->store(new Vendor($id, $name, $status));
    }
}
