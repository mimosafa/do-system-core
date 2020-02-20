<?php

namespace DoSystemTest\Application\Car\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Application\Car\Data\GetCarOutputInterface;
use DoSystem\Application\Car\Service\CreateCarService;
use DoSystem\Application\Car\Service\GetCarService;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

class CarServiceTest extends TestCase
{
    /**
     * @var CreateCarService
     */
    private $createService;

    /**
     * @var GetCarService
     */
    private $getService;

    /**
     * Mock data: vendor
     *
     * @var Vendor
     */
    private static $sampleVendor;

    /**
     * Mock data: vin
     *
     * @var string
     */
    private static $sampleVin = '品川500さ2345';

    /**
     * Mock data: name
     *
     * @var string
     */
    private static $sampleName = 'Test Car';

    protected function setUp(): void
    {
        $this->createService = doSystem()->make(CreateCarService::class);
        $this->getService = doSystem()->make(GetCarService::class);

        // setup vendor
        $repository = doSystem()->make(VendorRepositoryInterface::class);
        $vendor = new Vendor(
            VendorValueId::of(null),
            VendorValueName::of('Test Vendor'),
            VendorValueStatus::defaultStatus()
        );
        $id = $repository->store($vendor);
        self::$sampleVendor = $repository->findById($id);
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
     * @return CarValueId
     */
    public function testCreateCar()
    {
        $data = Mockery::mock('CreateCarInput', CreateCarInputInterface::class);
        $data->shouldReceive('getVendorId')->andReturn(self::$sampleVendor->getId()->getValue());
        $data->shouldReceive('getVin')->andReturn(self::$sampleVin);
        $data->shouldReceive('getName')->andReturn(self::$sampleName);

        $id = $this->createService->handle($data);

        $this->assertTrue($id instanceof CarValueId);

        return $id;
    }

    /**
     * @test
     * @depends testCreateCar
     *
     * @param CarValueId $id
     */
    public function testGetCar(CarValueId $id)
    {
        $model = $this->getService->handle($id);

        $this->assertTrue($model instanceof GetCarOutputInterface);
        $this->assertEquals($model->getVin()->getValue(), self::$sampleVin);
        $this->assertEquals($model->getName()->getValue(), self::$sampleName);
    }
}
