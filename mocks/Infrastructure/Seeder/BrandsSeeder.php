<?php

namespace DoSystemMock\Infrastructure\Seeder;

use Faker\Provider\Base as Faker;
use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Brand\Model\BrandValueName;
use DoSystem\Domain\Brand\Model\BrandValueOrder;
use DoSystem\Domain\Brand\Model\BrandValueStatus;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystemMock\Factory\BrandDataFactory;

class BrandsSeeder
{
    /**
     * @var int
     */
    private $numberOfBrands;
    private $numberOfVendors;

    /**
     * @var array[]
     */
    private $fakeData = [];

    /**
     * @var bool
     */
    private $done = false;

    /**
     * Constructor
     *
     * @param int $numberOfBrands
     * @param int $numberOfVendors
     */
    public function __construct(int $numberOfBrands, int $numberOfVendors = 0)
    {
        $this->numberOfBrands = $numberOfBrands;
        $this->numberOfVendors = $numberOfVendors ?: $this->numberOfBrands;
    }

    /**
     * @param BrandRepositoryInterface $brandRepository
     * @param VendorRepositoryInterface $vendorRepository
     * @return void
     */
    public function seed(BrandRepositoryInterface $brandRepository, VendorRepositoryInterface $vendorRepository)
    {
        if ($this->done) {
            return;
        }

        $vendorsSeeder = new VendorsSeeder($this->numberOfVendors);
        $vendorsSeeder->seed($vendorRepository);
        $vendorsData = $vendorsSeeder->getData();
        $vendorIds = \array_column($vendorsData, 'id');

        for ($i = 0; $i < $this->numberOfBrands; $i++) {
            $data = BrandDataFactory::generate(Faker::randomElement($vendorIds));
            $model = new Brand(
                BrandValueId::of(null),
                $vendorRepository->findById(VendorValueId::of($data['vendor_id'])),
                BrandValueName::of($data['name']),
                BrandValueStatus::of($data['status']),
                BrandValueOrder::of($data['order'])
            );
            $id = $brandRepository->store($model);
            $data['id'] = $id->getValue();
            $this->fakeData[] = $data;
        }

        $this->done = true;
    }

    /**
     * @return array[]
     */
    public function getData(): array
    {
        return $this->fakeData;
    }
}
