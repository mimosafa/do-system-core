<?php

namespace DoSystemTest\Domain\Vendor\Model;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystemMock\Infrastructure\Seeder\BrandsSeeder;
use DoSystemMock\Infrastructure\Seeder\CarsSeeder;

class VendorTest extends TestCase
{
    /**
     * @var VendorRepositoryInterface
     */
    private $repository;

    protected function setUp(): void
    {
        $this->repository ?? $this->repository = doSystem()->make(VendorRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        $this->repository->flush();
    }

    /**
     * @test
     */
    public function testGetBrands()
    {
        $repository = doSystem()->make(BrandRepositoryInterface::class);
        $seeder = new BrandsSeeder(8, 2);
        $seeder->seed($repository, $this->repository);
        $data = $seeder->getData();
        $vendors = $this->repository->query([]);

        $vendor1 = $vendors[0];
        $brandCollection = $vendor1->getBrands();

        $this->assertTrue($brandCollection instanceof BrandCollection);

        $vendorId1 = $vendor1->getId()->getValue();
        $belongsToVendor1Num = 0;
        foreach ($data as $arr) {
            if ($arr['vendor_id'] === $vendorId1) {
                $belongsToVendor1Num++;
            }
        }

        $this->assertEquals($brandCollection->count(), $belongsToVendor1Num);

        $repository->flush();
    }

    /**
     * @test
     */
    public function testGetCars()
    {
        $repository = doSystem()->make(CarRepositoryInterface::class);
        $seeder = new CarsSeeder(11, 3);
        $seeder->seed($repository, $this->repository);
        $data = $seeder->getData();
        $vendors = $this->repository->query([]);

        $vendor1 = $vendors[0];
        $carCollection = $vendor1->getCars();

        $this->assertTrue($carCollection instanceof CarCollection);

        $vendorId1 = $vendor1->getId()->getValue();
        $belongsToVendor1Num = 0;
        foreach ($data as $arr) {
            if ($arr['vendor_id'] === $vendorId1) {
                $belongsToVendor1Num++;
            }
        }

        $this->assertEquals($carCollection->count(), $belongsToVendor1Num);

        $repository->flush();
    }
}
