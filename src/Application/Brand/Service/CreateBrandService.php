<?php

namespace DoSystem\Application\Brand\Service;

use DoSystem\Application\Brand\Data\CreateBrandInputInterface;
use DoSystem\Core\Domain\Brand\Brand;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;
use DoSystem\Core\Domain\Brand\BrandValueName;
use DoSystem\Core\Domain\Brand\BrandValueOrder;
use DoSystem\Core\Domain\Brand\BrandValueStatus;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;

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

        $order = BrandValueOrder::of($input->getOrder());

        $id = BrandValueId::of(null);

        return $this->brandRepository->store(new Brand($id, $vendor, $name, $status, $order));
    }
}
