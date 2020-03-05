<?php

namespace DoSystemMock\Infrastructure\Seeder;

use Faker\Provider\Base as Faker;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueOrder;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystemMock\Factory\CarDataFactory;

class CarsSeeder
{
    /**
     * @var int
     */
    private $numberOfCars;
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
     * @param int $numberOfCars
     * @param int $numberOfVendors
     */
    public function __construct(int $numberOfCars, int $numberOfVendors = 0)
    {
        $this->numberOfCars = $numberOfCars;
        $this->numberOfVendors = $numberOfVendors ?: $this->numberOfCars;
    }

    /**
     * @param CarRepositoryInterface $carRepository
     * @param VendorRepositoryInterface $vendorRepository
     * @return void
     */
    public function seed(CarRepositoryInterface $carRepository, VendorRepositoryInterface $vendorRepository): void
    {
        if ($this->done) {
            return;
        }

        $vendorsSeeder = new VendorsSeeder($this->numberOfVendors);
        $vendorsSeeder->seed($vendorRepository);
        $vendorsData = $vendorsSeeder->getData();
        $vendorIds = \array_column($vendorsData, 'id');

        for ($i = 0; $i < $this->numberOfCars; $i++) {
            $data = CarDataFactory::generate(Faker::randomElement($vendorIds));
            $model = new Car(
                CarValueId::of(null),
                $vendorRepository->findById(VendorValueId::of($data['vendor_id'])),
                CarValueVin::of($data['vin']),
                CarValueStatus::of($data['status']),
                CarValueName::of($data['name']),
                CarValueOrder::of($data['order'])
            );
            $id = $carRepository->store($model);
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
