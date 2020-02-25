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
use DoSystemMock\Factory\VendorsFactory;

class VendorServiceTest extends TestCase
{
    /**
     * @var VendorsFactory
     */
    private static $factory;

    /**
     * Sample data for tests
     *
     * @var array
     */
    private static $sampleData;

    public static function setUpBeforeClass(): void
    {
        self::$factory = new VendorsFactory(20);
        self::$sampleData = self::$factory->provide();
    }

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
        $allFilter = doSystem()->make(QueryVendorFilterInterface::class);
        $allOutputs = $service->handle($allFilter);

        $this->assertTrue($allOutputs[0] instanceof QueriedVendorOutputInterface);
        $this->assertEquals(count($ids), count($allOutputs));

        // filter by name
        $nameFilter = doSystem()->makeWith(QueryVendorFilterInterface::class, [
            'name' => '株式会社',
        ]);
        $nameOutputs = $service->handle($nameFilter);

        $this->assertEquals(self::$factory->count株式会社(), count($nameOutputs));

        // filter by status
        $statusFilter = doSystem()->makeWith(QueryVendorFilterInterface::class, [
            'status' => [1, 3],
        ]);
        $statusOutputs = $service->handle($statusFilter);

        $this->assertEquals(self::$factory->countStatus(1) + self::$factory->countStatus(3), count($statusOutputs));

        // paged
        $pageFilter = doSystem()->makeWith(QueryVendorFilterInterface::class, [
            'sizePerPage' => 8,
            'page' => 3,
        ]);
        $pageOutputs = $service->handle($pageFilter);

        $this->assertEquals(4, count($pageOutputs)); // 20 - (8 * 2)
        $this->assertEquals(self::$sampleData[17 - 1]['name'], $pageOutputs[0]->getName()->getValue());
    }
}
