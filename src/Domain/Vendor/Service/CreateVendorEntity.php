<?php

namespace DoSystem\Domain\Vendor\Service;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;

class CreateVendorEntity
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
     * @param VendorValueName $name
     * @param VendorValueStatus|null $status
     * @return VendorValueId
     */
    public function handle(VendorValueName $name, ?VendorValueStatus $status = null): VendorValueId
    {
        $id = VendorValueId::of(null);

        if ($status === null) {
            $status = VendorValueStatus::defaultStatus();
        }

        $model = new Vendor($id, $name, $status);
        return $this->vendorRepository->store($model);
    }
}
