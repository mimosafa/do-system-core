<?php

namespace DoSystemCoreTest\Application\Kitchencar\Service;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Application\Kitchencar\Data;
use DoSystem\Core\Application\Kitchencar\Service;
use DoSystem\Core\Domain\Kitchencar;
use DoSystemCoreMock\Application\Kitchencar\Data as MockData;
use DoSystemCoreMock\Database\Seeder;
use DoSystemCoreMock\Infrastructure\Repository;

class KitchencarServiceTest extends TestCase
{
    /**
     * @var Repository\InMemoryKitchencarRepository
     */
    private $repository;

    /**
     * @var Repository\InMemoryBrandRepository
     */
    private $brandRepository;

    /**
     * @var Repository\InMemoryCarRepository
     */
    private $carRepository;

    /**
     * @var Repository\InMemoryVendorRepository
     */
    private $vendorRepository;

    protected function setUp(): void
    {
        $this->vendorRepository ?? $this->vendorRepository = new Repository\InMemoryVendorRepository();
        $this->brandRepository ?? $this->brandRepository = new Repository\InMemoryBrandRepository($this->vendorRepository);
        $this->carRepository ?? $this->carRepository = new Repository\InMemoryCarRepository($this->vendorRepository);
        $this->repository ?? $this->repository = new Repository\InMemoryKitchencarRepository($this->brandRepository, $this->carRepository, $this->vendorRepository);
    }

    protected function tearDown(): void
    {
        $this->repository->flush();
        $this->brandRepository->flush();
        $this->carRepository->flush();
        $this->vendorRepository->flush();
    }

    /**
     * Data seeder
     *
     * @access private
     *
     * @param int $kitchencarNum
     * @param int $brandNum
     * @param int $carNum
     * @param int $vendorNum
     * @return array
     */
    private function seed(int $kitchencarNum, int $brandNum = 0, int $carNum = 0, int $vendorNum = 0): array
    {
        if (!$brandNum) {
            $brandNum = $kitchencarNum;
        }
        if (!$carNum) {
            $carNum = $kitchencarNum;
        }
        if (!$vendorNum) {
            $vendorNum = $kitchencarNum;
        }
        $vendorsSeeder = (new Seeder\VendorsSeeder($vendorNum))->seed($this->vendorRepository);
        $brandsSeeder = (new Seeder\BrandsSeeder($brandNum, $vendorsSeeder))->seed($this->brandRepository, $this->vendorRepository);
        $carsSeeder = (new Seeder\CarsSeeder($carNum, $vendorsSeeder))->seed($this->carRepository, $this->vendorRepository);
        $kitchencarsSeeder = (new Seeder\KitchencarsSeeder($kitchencarNum, $brandsSeeder, $carsSeeder))->seed($this->repository, $this->brandRepository, $this->carRepository);

        return [
            'brands' => $brandsSeeder->get(),
            'cars' => $carsSeeder->get(),
            'kitchencars' => $kitchencarsSeeder->get(),
            'vendors' => $vendorsSeeder->get(),
        ];
    }

    /**
     * @test
     */
    public function testCreateKitchencar()
    {
        $service = new Service\CreateKitchencarService($this->repository, $this->brandRepository, $this->carRepository);

        $vendorsSeeder = (new Seeder\VendorsSeeder(1))->seed($this->vendorRepository);
        $brands = (new Seeder\BrandsSeeder(1, $vendorsSeeder))->seed($this->brandRepository, $this->vendorRepository)->get();
        $cars = (new Seeder\CarsSeeder(1, $vendorsSeeder))->seed($this->carRepository, $this->vendorRepository)->get();

        $input = new MockData\CreateKitchencarInputMock();
        $input->brandId = $brands[0]['id'];
        $input->carId = $cars[0]['id'];

        $id = $service->handle($input);

        $this->assertTrue($id instanceof Kitchencar\KitchencarValueId);
    }

    /**
     * @test
     */
    public function testGetKitchencar()
    {
        $service = new Service\GetKitchencarService($this->repository);

        $data = $this->seed(8, 5, 3, 4);
        $kitchencarData4 = $data['kitchencars'][4];
        $id4 = $kitchencarData4['id'];

        $output = $service->handle(Kitchencar\KitchencarValueId::of($id4));

        $this->assertTrue($output instanceof Data\GetKitchencarOutputInterface);
        $this->assertEquals($kitchencarData4['brand_id'], $output->model->getBrand()->getId()->getValue());
        $this->assertEquals($kitchencarData4['car_id'], $output->model->getCar()->getId()->getValue());
        $this->assertEquals($kitchencarData4['order'], $output->model->getOrder()->getValue());
    }
}
