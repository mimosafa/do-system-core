<?php

namespace DoSystemTest\Application\Vendor\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Vendor\Data\CreateVendorInputInterface;
use DoSystem\Application\Vendor\Data\GetVendorOutputInterface;
use DoSystem\Application\Vendor\Data\QueriedVendorOutputInterface;
use DoSystem\Application\Vendor\Data\QueryVendorFilterInterface;
use DoSystem\Application\Vendor\Service\CreateVendorService;
use DoSystem\Application\Vendor\Service\GetVendorService;
use DoSystem\Application\Vendor\Service\QueryVendorService;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorCollection;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

class VendorServiceTest extends TestCase
{
    /**
     * Sample data for tests
     */
    private static $sampleData = [
        /* 0 => */ [ /*'id' => 1, */ 'name' => 'Test Vendor', 'status' => 4],
        /* 1 => */ [ /*'id' => 2, */ 'name' => 'McDonald',    'status' => 3],
        /* 2 => */ [ /*'id' => 3, */ 'name' => 'Mos Buarger', 'status' => 3],
        /* 3 => */ [ /*'id' => 4, */ 'name' => 'Tokyo Do',    'status' => 4],
        /* 4 => */ [ /*'id' => 5, */ 'name' => 'Mellow',      'status' => 5],
        /* 5 => */ [ /*'id' => 6, */ 'name' => 'New Face',    'status' => 1],
    ];

    /**
     * Flush VendorRepository
     */
    public static function tearDownAfterClass(): void
    {
        doSystem()->make(VendorRepositoryInterface::class)->flush();
    }

    /**
     * @test
     *
     * @return VendorValueId[]
     */
    public function testCreateVendor(): array
    {
        $service = doSystem()->make(CreateVendorService::class);

        $ids = [];
        foreach (self::$sampleData as $array) {
            $data = Mockery::mock('CreateVendorInput', CreateVendorInputInterface::class);
            $data->shouldReceive('getName')->andReturn($array['name']);
            $data->shouldReceive('getStatus')->andReturn($array['status']);
            $id = $service->handle($data);

            $this->assertTrue($id instanceof VendorValueId);

            $ids[] = $id;
        }
        return $ids;
    }

    /**
     * @test
     * @depends testCreateVendor
     *
     * @param VendorValueId[] $ids
     */
    public function testGetVendor(array $ids)
    {
        $service = doSystem()->make(GetVendorService::class);

        foreach ($ids as $i => $id) {
            $output = $service->handle($id);

            $this->assertTrue($output instanceof GetVendorOutputInterface);
            $this->assertEquals($output->getName()->getValue(), self::$sampleData[$i]['name']);
            $this->assertEquals($output->getStatus()->getValue(), self::$sampleData[$i]['status']);
        }
    }

    /**
     * @test
     * @depends testCreateVendor
     *
     * @param VendorValueId[] $ids
     */
    public function testQueryVendor(array $ids)
    {
        $service = doSystem()->make(QueryVendorService::class);

        // all
        $allFilter = Mockery::mock('QueryVendorFilterAll', QueryVendorFilterInterface::class);
        $allFilter->shouldReceive('getNameFilter')->andReturn(null);
        $allFilter->shouldReceive('getStatusFilter')->andReturn(null);
        $allFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $allOutputs = $service->handle($allFilter);

        $this->assertTrue($allOutputs[0] instanceof QueriedVendorOutputInterface);
        $this->assertEquals(count($ids), count($allOutputs));

        // filter by name
        $nameFilter = Mockery::mock('QueryVendorFilterName', QueryVendorFilterInterface::class);
        $nameFilter->shouldReceive('getNameFilter')->andReturn('Do'); // will be matched 'McDonald' & 'Tokyo Do'
        $nameFilter->shouldReceive('getStatusFilter')->andReturn(null);
        $nameFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $nameOutputs = $service->handle($nameFilter);

        $this->assertEquals(2, count($nameOutputs));

        // filter by status
        $statusFilter = Mockery::mock('QueryVendorFilterStatus', QueryVendorFilterInterface::class);
        $statusFilter->shouldReceive('getNameFilter')->andReturn(null);
        $statusFilter->shouldReceive('getStatusFilter')->andReturn([1, 3]); // will be matched 'McDonald' & 'Mos Buarger' & 'New Face'
        $statusFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $statusOutputs = $service->handle($statusFilter);

        $this->assertEquals(3, count($statusOutputs));

        // paged
        $pageFilter = Mockery::mock('QueryVendorFilterPaged', QueryVendorFilterInterface::class);
        $pageFilter->shouldReceive('getNameFilter')->andReturn(null);
        $pageFilter->shouldReceive('getStatusFilter')->andReturn(null);
        $pageFilter->shouldReceive('getSizePerPage')->andReturn(4);
        $pageFilter->shouldReceive('getPage')->andReturn(2);
        $pageOutputs = $service->handle($pageFilter);

        $this->assertEquals(2, count($pageOutputs));
        $this->assertEquals('Mellow', $pageOutputs[0]->getName()->getValue());
    }
}
