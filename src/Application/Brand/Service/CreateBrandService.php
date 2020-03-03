<?php

namespace DoSystem\Application\Brand\Service;

use DoSystem\Application\Brand\Data\CreateBrandInputInterface;
use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Brand\Model\BrandValueName;
use DoSystem\Domain\Brand\Model\BrandValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;

class CreateBrandService
{
    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepository;

    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * Constructor
     *
     * @param BrandRepositoryInterface $brandRepository
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(BrandRepositoryInterface $brandRepository, VendorRepositoryInterface $vendorRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param CreateBrandInputInterface $input
     * @return BrandValueId
     */
    public function handle(CreateBrandInputInterface $input): BrandValueId
    {
        $vendorId = $input->getVendorId();
        $vendor = $this->vendorRepository->findById(VendorValueId::of($vendorId));
        $name = BrandValueName::of($input->getName());

        $statusInt = $input->getStatus();
        $status = isset($statusInt) ? BrandValueStatus::of($statusInt) : BrandValueStatus::default();

        $id = BrandValueId::of(null);

        return $this->brandRepository->store(new Brand($id, $vendor, $name, $status));
    }
}
