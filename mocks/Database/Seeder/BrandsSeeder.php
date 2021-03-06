<?php

namespace DoSystemCoreMock\Database\Seeder;

use DoSystem\Core\Domain\Brand\Brand;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;
use DoSystem\Core\Domain\Brand\BrandValueName;
use DoSystem\Core\Domain\Brand\BrandValueOrder;
use DoSystem\Core\Domain\Brand\BrandValueStatus;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystemCoreMock\Database\Factory\BrandDataFactory;

class BrandsSeeder
{
    use SeederTrait;

    /**
     * Number of data
     *
     * @var int
     */
    private $num;

    /**
     * Vendor ids
     *
     * @var int[]
     */
    private $vendorIds;

    /**
     * Constructor
     *
     * @param int $num
     * @param VendorsSeeder $vendorsSeeder
     * @throws \Exception
     */
    public function __construct(int $num, VendorsSeeder $vendorsSeeder)
    {
        if ($num < 1) {
            throw new \Exception('Parameter of ' . __METHOD__ . ' must be positive integer.');
        }
        $this->num = $num;
        $vendorData = $vendorsSeeder->get();
        $this->vendorIds = \array_column($vendorData, 'id');
    }

    /**
     * Seed data to repository
     *
     * @param BrandRepositoryInterface $brandRepository
     * @param VendorRepositoryInterface $vendorRepository
     * @return self|null
     */
    public function seed(BrandRepositoryInterface $brandRepository, VendorRepositoryInterface $vendorRepository): ?self
    {
        if (!empty($this->data)) {
            return null;
        }

        for ($i = 0; $i < $this->num; $i++) {
            $data = BrandDataFactory::generate($this->vendorIds);
            $model = new Brand(
                BrandValueId::of(null),
                $vendorRepository->findById(VendorValueId::of($data['vendor_id'])),
                BrandValueName::of($data['name']),
                BrandValueStatus::of($data['status']),
                BrandValueOrder::of($data['order'])
            );
            $data['id'] = $brandRepository->store($model)->getValue();
            $this->data[] = $data;
        }

        return $this;
    }
}
