<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;

use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;       /** @todo DI */
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface; /** @todo DI */
use DoSystem\InMemory\Repositories\CarRepository;           /** @todo DI */
use DoSystem\InMemory\Repositories\VendorRepository;        /** @todo DI */

class CarRepositoryTest extends TestCase
{
    /**
     * @var CarRepositoryInterface
     */
    protected $repository;

    /**
     * Mock vendor entity
     *
     * @var Vendor
     */
    protected $vendor1;
    protected $vendor2;

    /**
     * Mock data
     */
    protected $data = [
        'vendor1' => [
            [
                'vin' => '品川800あ1234',
            ],
            [
                'vin' => '足立40い5678',
            ],
        ],
        'vendor2' => [
            [
                'vin' => '高知88い9999',
            ],
        ],
    ];

    /**
     * Cache for IDs of created Car entity
     *
     * @var CarValueId[]
     */
    protected $ids = [];

    protected function setUp(): void
    {
        $vendorRepository = new VendorRepository();

        $vendorId1 = $vendorRepository->store(new Vendor(
            VendorValueId::of(null),
            VendorValueName::of('TokyoDo')
        ));
        $this->vendor1 = $vendorRepository->findById($vendorId1);

        $vendorId2 = $vendorRepository->store(new Vendor(
            VendorValueId::of(null),
            VendorValueName::of('Workstore')
        ));
        $this->vendor2 = $vendorRepository->findById($vendorId2);

        $this->repository = new CarRepository($vendorRepository);

        foreach ($this->data as $vendorKey => $array) {
            $vendor = $this->{$vendorKey};
            foreach ($array as $args) {
                $id = CarValueId::of(null);
                $vin = CarValueVin::of($args['vin']);

                $model = new Car($id, $vendor, $vin);
                $this->ids[] = $this->repository->store($model);
            }
        }
    }

    /**
     * @test
     */
    public function testRepository()
    {
        $this->assertTrue($this->repository instanceof CarRepositoryInterface);
    }

    /**
     * @test
     */
    public function testStoreNewEntity()
    {
        $pseudoId = CarValueId::of(null);
        $vendor = $this->vendor2;
        $vin = CarValueVin::of('習志野50ん1');

        $car = new Car($pseudoId, $vendor, $vin);
        $id = $this->repository->store($car);

        $this->assertTrue($id instanceof CarValueId);

        return $id;
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $id = $this->ids[0];
        $model = $this->repository->findById($id);

        $this->assertTrue($model instanceof Car);
    }

    /**
     * @test
     */
    public function testFind()
    {
        $collection = $this->repository->find();

        $this->assertTrue($collection instanceof CarCollection);
    }
}
