<?php

namespace DoSystemTest\Application\Car\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Application\Car\Data\GetCarOutputInterface;
use DoSystem\Application\Car\Data\QueryCarFilterInterface;
use DoSystem\Application\Car\Data\QueriedCarOutputInterface;
use DoSystem\Application\Car\Service\CreateCarService;
use DoSystem\Application\Car\Service\GetCarService;
use DoSystem\Application\Car\Service\QueryCarService;
use DoSystem\Application\Vendor\Data\CreateVendorInputInterface;
use DoSystem\Application\Vendor\Service\CreateVendorService;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystemMock\Factory\CarsFactory;
use DoSystemMock\Factory\VendorsFactory;

class CarServiceTest extends TestCase
{
    /**
     * @var CarsFactory
     */
    private static $factory;

    /**
     * Sample data for tests
     *
     * @var array
     */
    private static $sampleData;

    /**
     * Sample Car data for tests
     */
    private static $sampleCarData = [
        /* 0 => */ [ /*'id' => 1, */ 'vendor_id' => 1, 'vin' => '品川500さ2345', 'status' => 3, 'name' => 'Test Car'],
        /* 1 => */ [ /*'id' => 2, */ 'vendor_id' => 1, 'vin' => '多摩500さ4649', 'status' => 4, 'name' => 'DeLorean'],
        /* 2 => */ [ /*'id' => 3, */ 'vendor_id' => 2, 'vin' => '京都500あ4649', 'status' => 3, 'name' => 'Benz'],
        /* 3 => */ [ /*'id' => 4, */ 'vendor_id' => 3, 'vin' => '北見580あ4649', 'status' => 5, 'name' => 'Crown'],
        /* 4 => */ [ /*'id' => 5, */ 'vendor_id' => 1, 'vin' => '鹿児島480り4649', 'status' => 3, 'name' => 'Delica'],
        /* 5 => */ [ /*'id' => 6, */ 'vendor_id' => 3, 'vin' => '品川500り4649', 'status' => 4, 'name' => 'Super Car'],
    ];

    /**
     * Prepare Vendor repository before tests
     */
    public static function setUpBeforeClass(): void
    {
        $vendorsFactory = new VendorsFactory(5);
        $vendorSampleData = $vendorsFactory->provide();
        $service = doSystem()->make(CreateVendorService::class);

        foreach ($vendorSampleData as $array) {
            $data = Mockery::mock('CreateVendorInput', CreateVendorInputInterface::class);
            $data->shouldReceive('getName')->andReturn($array['name']);
            $data->shouldReceive('getStatus')->andReturn($array['status']);
            $service->handle($data);
        }

        $vendorRepository = doSystem()->make(VendorRepositoryInterface::class);
        /** @see \DoSystemMock\InMemoryInfrastructure\VendorRepositoryMock::getIds() */
        $vendorIds = $vendorRepository->getIds();
        self::$factory = new CarsFactory(20, $vendorIds);
        self::$sampleData = self::$factory->provide();
    }

    /**
     * Flush VendorRepository
     */
    public static function tearDownAfterClass(): void
    {
        doSystem()->make(VendorRepositoryInterface::class)->flush();
        doSystem()->make(CarRepositoryInterface::class)->flush();
    }

    /**
     * @test
     *
     * @return CarValueId[]
     */
    public function testCreateCar(): array
    {
        $service = doSystem()->make(CreateCarService::class);

        $ids = [];
        foreach (self::$sampleData as $array) {
            $data = Mockery::mock('CreateCarInput', CreateCarInputInterface::class);
            $data->shouldReceive('getVendorId')->andReturn($array['vendor_id']);
            $data->shouldReceive('getVin')->andReturn($array['vin']);
            $data->shouldReceive('getStatus')->andReturn($array['status']);
            $data->shouldReceive('getName')->andReturn($array['name']);
            $id = $service->handle($data);

            $this->assertTrue($id instanceof CarValueId);

            $ids[] = $id;
        }
        return $ids;
    }

    /**
     * @test
     * @depends testCreateCar
     *
     * @param CarValueId[] $ids
     */
    public function testGetCar(array $ids)
    {
        $service = doSystem()->make(GetCarService::class);

        foreach ($ids as $i => $id) {
            $output = $service->handle($id);

            $this->assertTrue($output instanceof GetCarOutputInterface);
            $this->assertEquals($output->getVin()->getValue(), self::$sampleData[$i]['vin']);
            $this->assertEquals($output->getName()->getValue(), self::$sampleData[$i]['name']);
        }
    }

    /**
     * @test
     * @depends testCreateCar
     *
     * @param CarValueId[] $ids
     */
    public function testQueryCar(array $ids)
    {
        $service = doSystem()->make(QueryCarService::class);

        // all
        $allFilter = doSystem()->make(QueryCarFilterInterface::class);
        $allOutputs = $service->handle($allFilter);

        $this->assertTrue($allOutputs[0] instanceof QueriedCarOutputInterface);
        $this->assertEquals(count($ids), count($allOutputs));

        // filter by vendor
        $vendorFilter = doSystem()->makeWith(QueryCarFilterInterface::class, [
            'vendorId' => [1, 4],
        ]);
        $vendorOutputs = $service->handle($vendorFilter);

        $this->assertEquals(count($vendorOutputs), self::$factory->countByVendorId(1) + self::$factory->countByVendorId(4));

        // filter by vin
        $vinFilter = doSystem()->makeWith(QueryCarFilterInterface::class, [
            'vin' => '品川',
        ]);
        $vinOutputs = $service->handle($vinFilter);

        $this->assertEquals(count($vinOutputs), self::$factory->countByVin品川());

        // filter by status
        $statusFilter = doSystem()->makeWith(QueryCarFilterInterface::class, [
            'status' => [0, 7],
        ]);
        $statusOutput = $service->handle($statusFilter);

        $this->assertEquals(count($statusOutput), self::$factory->countByStatus(0) + self::$factory->countByStatus(7));

        // paged
        $pageFilter = doSystem()->makeWith(QueryCarFilterInterface::class, [
            'sizePerPage' => 6,
            'page' => 4,
        ]);
        $pageOutputs = $service->handle($pageFilter);

        $this->assertEquals(2, count($pageOutputs)); // 20 - (6 * (4 - 1))
        $this->assertEquals(self::$sampleData[6 * 3 + 1 - 1]['vin'], $pageOutputs[0]->getVin()->getValue());
    }
}
