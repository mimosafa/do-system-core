<?php

namespace DoSystemTest\Domain\Vendor\Model;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystemMock\Infrastructure\Repository\InMemoryBrandRepository;
use DoSystemMock\Infrastructure\Repository\InMemoryCarRepository;
use DoSystemMock\Infrastructure\Repository\InMemoryVendorRepository;
use DoSystemMock\Database\Seeder\BrandsSeeder;
use DoSystemMock\Database\Seeder\VendorsSeeder;
use DoSystemMock\Database\Seeder\CarsSeeder;

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
