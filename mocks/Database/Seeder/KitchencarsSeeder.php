<?php

namespace DoSystemMock\Database\Seeder;

use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueId;
use DoSystem\Core\Domain\Kitchencar\Kitchencar;
use DoSystem\Core\Domain\Kitchencar\KitchencarRepositoryInterface;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueId;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueOrder;
use DoSystemMock\Database\Factory\KitchencarDataFactory;

class KitchencarsSeeder
{
    use SeederTrait;

    /**
     * Number of data
     *
     * @var int
     */
    private $num;

    /**
     * @var array
     */
    private $ids = [];

    /**
     * Constructor
     *
     * @param int $num
     * @param BrandsSeeder $brandsSeeder
     * @param CarsSeeder $carsSeeder
     * @throws \Exception
     */
    public function __construct(int $num, BrandsSeeder $brandsSeeder, CarsSeeder $carsSeeder)
    {
        if ($num < 1) {
            throw new \Exception('Parameter of ' . __METHOD__ . ' must be positive integer.');
        }
        $this->num = $num;
        $this->initIdsMap($brandsSeeder, $carsSeeder);
    }

    /**
     * Generate brand ID & car ID map, indexed by vendor ID
     *
     * @param BrandsSeeder $brandsSeeder
     * @param CarsSeeder $carsSeeder
     * @return void
     */
    private function initIdsMap(BrandsSeeder $brandsSeeder, CarsSeeder $carsSeeder): void
    {
        $brands = $brandsSeeder->get();
        $cars = $carsSeeder->get();

        foreach ($brands as $brandRow) {
            $vendorId = $brandRow['vendor_id'];
            if (!isset($this->ids[$vendorId])) {
                $this->ids[$vendorId] = ['brand' => []];
            }
            $this->ids[$vendorId]['brand'][] = $brandRow['id'];
        }

        foreach ($cars as $carRow) {
            $vendorId = $carRow['vendor_id'];
            if (!isset($this->ids[$vendorId])) {
                $this->ids[$vendorId] = ['car' => []];
            }
            $this->ids[$vendorId]['car'][] = $carRow['id'];
        }

        $this->ids = \array_filter($this->ids, function ($array) {
            return isset($array['brand']) && isset($array['car']);
        });
    }

    /**
     * Seed data to repository
     *
     * @param KitchencarRepositoryInterface $repository
     * @param BrandRepositoryInterface $brandRepository
     * @param CarRepositoryInterface $carRepository
     * @return self|null
     */
    public function seed(KitchencarRepositoryInterface $repository, BrandRepositoryInterface $brandRepository, CarRepositoryInterface $carRepository)
    {
        if (!empty($this->data)) {
            return null;
        }

        for ($i = 0; $i < $this->num; $i++) {
            $data = KitchencarDataFactory::generate($this->ids);
            $model = new Kitchencar(
                KitchencarValueId::of(null),
                $brandRepository->findById(BrandValueId::of($data['brand_id'])),
                $carRepository->findById(CarValueId::of($data['car_id'])),
                KitchencarValueOrder::of($data['order'])
            );
            $data['id'] = $repository->store($model)->getValue();
            $this->data[] = $data;
        }

        return $this;
    }
}
