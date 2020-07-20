<?php

namespace DoSystemCoreTest\Domain\Vendor;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Car\CarCollection;
use DoSystemCoreMock\Infrastructure\Repository\InMemoryBrandRepository;
use DoSystemCoreMock\Infrastructure\Repository\InMemoryCarRepository;
use DoSystemCoreMock\Infrastructure\Repository\InMemoryVendorRepository;
use DoSystemCoreMock\Database\Seeder\BrandsSeeder;
use DoSystemCoreMock\Database\Seeder\VendorsSeeder;
use DoSystemCoreMock\Database\Seeder\CarsSeeder;

class VendorTest extends TestCase
{
    /**
     * @var VendorRepositoryInterface
     */
    private $repository;

    protected function setUp(): void
    {
        $this->repository ?? $this->repository = new InMemoryVendorRepository();
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
        $repository = new InMemoryBrandRepository($this->repository);
        $seeder = new BrandsSeeder(8, (new VendorsSeeder(2))->seed($this->repository));
        $data = $seeder->seed($repository, $this->repository)->get();

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
        $repository = new InMemoryCarRepository($this->repository);
        $seeder = new CarsSeeder(11, (new VendorsSeeder(3))->seed($this->repository));
        $data = $seeder->seed($repository, $this->repository)->get();

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
