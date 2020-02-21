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

class CarServiceTest extends TestCase
{
    /**
     * Sample Vendor data for tests
     */
    private static $sampleVendorData = [
        /* 0 => */ [ /*'id' => 1, */ 'name' => 'Test Vendor', 'status' => 4],
        /* 1 => */ [ /*'id' => 2, */ 'name' => 'McDonald',    'status' => 3],
        /* 2 => */ [ /*'id' => 3, */ 'name' => 'Mos Buarger', 'status' => 3],
    ];

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
        $service = doSystem()->make(CreateVendorService::class);

        foreach (self::$sampleVendorData as $array) {
            $data = Mockery::mock('CreateVendorInput', CreateVendorInputInterface::class);
            $data->shouldReceive('getName')->andReturn($array['name']);
            $data->shouldReceive('getStatus')->andReturn($array['status']);
            $service->handle($data);
        }
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
        foreach (self::$sampleCarData as $array) {
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
            $this->assertEquals($output->getVin()->getValue(), self::$sampleCarData[$i]['vin']);
            $this->assertEquals($output->getName()->getValue(), self::$sampleCarData[$i]['name']);
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
        $allFilter = Mockery::mock('QueryCarFilterAll', QueryCarFilterInterface::class);
        $allFilter->shouldReceive('getVendorIdFilter')->andReturn(null);
        $allFilter->shouldReceive('getVinFilter')->andReturn(null);
        $allFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $allOutputs = $service->handle($allFilter);

        $this->assertTrue($allOutputs[0] instanceof QueriedCarOutputInterface);
        $this->assertEquals(count($ids), count($allOutputs));

        // filter by vendor
        $vendorFilter = Mockery::mock('QueryCarFilterVendor', QueryCarFilterInterface::class);
        $vendorFilter->shouldReceive('getVendorIdFilter')->andReturn([3]); // will be matched 'Crown' & 'Super Car'
        $vendorFilter->shouldReceive('getVinFilter')->andReturn(null);
        $vendorFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $vendorOutputs = $service->handle($vendorFilter);

        $this->assertEquals(2, count($vendorOutputs));

        // filter by vin
        $vinFilter = Mockery::mock('QueryCarFilterVin', QueryCarFilterInterface::class);
        $vinFilter->shouldReceive('getVendorIdFilter')->andReturn(null);
        $vinFilter->shouldReceive('getVinFilter')->andReturn('品川'); // will be matched 'Test Car' & 'Super Car'
        $vinFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $vinOutputs = $service->handle($vinFilter);

        $this->assertEquals(2, count($vinOutputs));

        // paged
        $pageFilter = Mockery::mock('QueryCarFilterVin', QueryCarFilterInterface::class);
        $pageFilter->shouldReceive('getVendorIdFilter')->andReturn(null);
        $pageFilter->shouldReceive('getVinFilter')->andReturn(null);
        $pageFilter->shouldReceive('getSizePerPage')->andReturn(4);
        $pageFilter->shouldReceive('getPage')->andReturn(2);
        $pageOutputs = $service->handle($pageFilter);

        $this->assertEquals(2, count($pageOutputs));
        $this->assertEquals('鹿児島480り4649', $pageOutputs[0]->getVin()->getValue());
    }
}
