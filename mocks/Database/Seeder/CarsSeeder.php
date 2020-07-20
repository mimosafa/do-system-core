<?php

namespace DoSystemMock\Database\Seeder;

use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueOrder;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystemMock\Database\Factory\CarDataFactory;

class CarsSeeder
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
     * @param CarRepositoryInterface $carRepository
     * @param VendorRepositoryInterface $vendorRepository
     * @return self|null
     */
    public function seed(CarRepositoryInterface $carRepository, VendorRepositoryInterface $vendorRepository): ?self
    {
        if (!empty($this->data)) {
            return null;
        }

        for ($i = 0; $i < $this->num; $i++) {
            $data = CarDataFactory::generate($this->vendorIds);
            $model = new Car(
                CarValueId::of(null),
                $vendorRepository->findById(VendorValueId::of($data['vendor_id'])),
                CarValueVin::of($data['vin']),
                CarValueStatus::of($data['status']),
                CarValueName::of($data['name']),
                CarValueOrder::of($data['order'])
            );
            $data['id'] = $carRepository->store($model)->getValue();
            $this->data[] = $data;
        }

        return $this;
    }
}
