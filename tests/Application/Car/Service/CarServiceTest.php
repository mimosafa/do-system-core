<?php

namespace DoSystemTest\Application\Car\Service;

use Faker\Provider\Base as Faker;
use Mockery;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Application\Car\Data\GetCarOutputInterface;
use DoSystem\Application\Car\Data\QueryCarFilterInterface;
use DoSystem\Application\Car\Data\QueriedCarOutputInterface;
use DoSystem\Application\Car\Data\UpdateCarOutputInterface;
use DoSystem\Application\Car\Service\CreateCarService;
use DoSystem\Application\Car\Service\GetCarService;
use DoSystem\Application\Car\Service\QueryCarService;
use DoSystem\Application\Car\Service\UpdateCarService;
use DoSystem\Application\Vendor\Data\CreateVendorInputInterface;
use DoSystem\Application\Vendor\Service\CreateVendorService;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystemMock\Application\Car\Data\UpdateCarInputMock;
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
     * @return CarValueId[]
     */
    public function testGetCar(array $ids): array
    {
        $service = doSystem()->make(GetCarService::class);

        foreach ($ids as $i => $id) {
            $output = $service->handle($id);

            $this->assertTrue($output instanceof GetCarOutputInterface);
            $this->assertEquals($output->getVin()->getValue(), self::$sampleData[$i]['vin']);
            $this->assertEquals($output->getName()->getValue(), self::$sampleData[$i]['name']);
        }

        return $ids;
    }

    /**
     * @test
     * @depends testGetCar
     *
     * @param CarValueId[] $ids
     * @return CarValueId[]
     */
    public function testQueryCar(array $ids): array
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

        return $ids;
    }

    /**
     * @test
     * @depends testQueryCar
     *
     * @param CarValueId[] $ids
     */
    public function testUpdateCar(array $ids)
    {
        $getService = doSystem()->make(GetCarService::class);
        $updateService = doSystem()->make(UpdateCarService::class);
        $updateIds = Faker::randomElements($ids, 3);

        // Update vin
        $id1 = $updateIds[0];
        $vinAfter = Faker::regexify(CarValueVin::getRegexPattern());
        $model1 = $getService->handle($id1);
        $vinBefore = $model1->getVin()->getValue();

        $vinInput = new UpdateCarInputMock();
        $vinInput->id = $id1->getValue();
        $vinInput->vin = $vinAfter;
        $vinOutput = $updateService->handle($vinInput);

        $this->assertTrue($vinOutput instanceof UpdateCarOutputInterface);
        $this->assertNotEquals($vinBefore, $getService->handle($id1)->getVin()->getValue());
        $this->assertEquals(1, count($vinOutput->modified));
        $this->assertEquals($vinOutput->modified[0], CarValueVin::class);

        // Update name
        $id2 = $updateIds[1];
        $nameAfter = 'Awesome Kitchencar';
        $model2 = $getService->handle($id2);
        $nameBefore = $model2->getName()->getValue();

        $nameInput = new UpdateCarInputMock();
        $nameInput->id = $id2->getValue();
        $nameInput->name = $nameAfter;
        $nameOutput = $updateService->handle($nameInput);

        $this->assertNotEquals($nameBefore, $getService->handle($id2)->getName()->getValue());
        $this->assertEquals(1, count($nameOutput->modified));
        $this->assertEquals($nameOutput->modified[0], CarValueName::class);
    }
}
