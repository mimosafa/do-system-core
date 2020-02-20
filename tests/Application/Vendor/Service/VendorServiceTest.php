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
     * @var CreateVendorService
     */
    private $createService;

    /**
     * @var GetVendorService
     */
    private $getService;

    /**
     * @var QueryVendorService
     */
    private $queryService;

    private $sampleData = [
        /* 0 => */ [ /*'id' => 1, */ 'name' => 'Test Vendor', 'status' => 4],
        /* 1 => */ [ /*'id' => 2, */ 'name' => 'McDonald',    'status' => 3],
        /* 2 => */ [ /*'id' => 3, */ 'name' => 'Mos Buarger', 'status' => 3],
        /* 3 => */ [ /*'id' => 4, */ 'name' => 'Tokyo Do',    'status' => 4],
        /* 4 => */ [ /*'id' => 5, */ 'name' => 'Mellow',      'status' => 5],
        /* 5 => */ [ /*'id' => 6, */ 'name' => 'New Face',    'status' => 1],
    ];

    /**
     * Mock data: name
     *
     * @var string
     */
    private static $sampleName = 'Test Vendor';

    /**
     * Mock data: status
     *
     * @var int
     */
    private static $sampleStatus;

    protected function setUp(): void
    {
        $this->createService = doSystem()->make(CreateVendorService::class);
        $this->getService = doSystem()->make(GetVendorService::class);
        $this->queryService = doSystem()->make(QueryVendorService::class);
    }

    /**
     * Flush VendorRepository
     */
    public static function tearDownAfterClass(): void
    {
        $repository = doSystem()->make(VendorRepositoryInterface::class);
        $repository->flush();
    }

    /**
     * @test
     *
     * @return VendorValueId
     */
    public function testCreateVendor()
    {
        $ids = [];

        foreach ($this->sampleData as $array) {
            $data = Mockery::mock('CreateVendorInput', CreateVendorInputInterface::class);
            $data->shouldReceive('getName')->andReturn($array['name']);
            $data->shouldReceive('getStatus')->andReturn($array['status']);
            $id = $this->createService->handle($data);

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
        foreach ($ids as $i => $id) {
            $output = $this->getService->handle($id);

            $this->assertTrue($output instanceof GetVendorOutputInterface);
            $this->assertEquals($output->getName()->getValue(), $this->sampleData[$i]['name']);
            $this->assertEquals($output->getStatus()->getValue(), $this->sampleData[$i]['status']);
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
        // all
        $allFilter = Mockery::mock('QueryVendorFilterAll', QueryVendorFilterInterface::class);
        $allFilter->shouldReceive('getNameFilter')->andReturn(null);
        $allFilter->shouldReceive('getStatusFilter')->andReturn(null);
        $allFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $collectionAll = $this->queryService->handle($allFilter);

        $this->assertTrue($collectionAll[0] instanceof QueriedVendorOutputInterface);
        $this->assertEquals(count($ids), count($collectionAll));

        // filter by name
        $nameFilter = Mockery::mock('QueryVendorFilterAll', QueryVendorFilterInterface::class);
        $nameFilter->shouldReceive('getNameFilter')->andReturn('Do'); // will be matched 'McDonald' & 'Tokyo Do'
        $nameFilter->shouldReceive('getStatusFilter')->andReturn(null);
        $nameFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $collectionFilteredByName = $this->queryService->handle($nameFilter);

        $this->assertEquals(2, count($collectionFilteredByName));

        // filter by status
        $statusFilter = Mockery::mock('QueryVendorFilterAll', QueryVendorFilterInterface::class);
        $statusFilter->shouldReceive('getNameFilter')->andReturn(null);
        $statusFilter->shouldReceive('getStatusFilter')->andReturn([1, 3]); // will be matched 'McDonald' & 'Mos Buarger' & 'New Face'
        $statusFilter->shouldReceive('getSizePerPage')->andReturn(null);
        $collectionFilteredByStatus = $this->queryService->handle($statusFilter);

        $this->assertEquals(3, count($collectionFilteredByStatus));

        // paged
        $pageFilter = Mockery::mock('QueryVendorFilterAll', QueryVendorFilterInterface::class);
        $pageFilter->shouldReceive('getNameFilter')->andReturn(null);
        $pageFilter->shouldReceive('getStatusFilter')->andReturn(null);
        $pageFilter->shouldReceive('getSizePerPage')->andReturn(4);
        $pageFilter->shouldReceive('getPage')->andReturn(2);
        $collectionPage2 = $this->queryService->handle($pageFilter);

        $this->assertEquals(2, count($collectionPage2));
        $this->assertEquals('Mellow', $collectionPage2[0]->getName()->getValue());
    }
}
