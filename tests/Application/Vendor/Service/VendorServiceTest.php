<?php

namespace DoSystemTest\Application\Vendor\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Vendor\Data\CreateVendorInputInterface;
use DoSystem\Application\Vendor\Data\GetVendorOutputInterface;
use DoSystem\Application\Vendor\Service\CreateVendorService;
use DoSystem\Application\Vendor\Service\GetVendorService;
use DoSystem\Domain\Vendor\Model\Vendor;
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
    }

    /**
     * @test
     *
     * @return VendorValueId
     */
    public function testCreateVendor()
    {
        // take status value at random
        $statusValues = VendorValueStatus::values();
        $keys = array_keys($statusValues);
        shuffle($keys);
        self::$sampleStatus = $statusValues[$keys[0]]->getValue();

        $data = Mockery::mock('CreateVendorInput', CreateVendorInputInterface::class);
        $data->shouldReceive('getName')->andReturn(self::$sampleName);
        $data->shouldReceive('getStatus')->andReturn(self::$sampleStatus);

        $id = $this->createService->handle($data);

        $this->assertTrue($id instanceof VendorValueId);

        return $id->getValue();
    }

    /**
     * @test
     * @depends testCreateVendor
     *
     * @param int $id
     */
    public function testGetVendor(int $id)
    {
        $model = $this->getService->handle($id);

        $this->assertTrue($model instanceof GetVendorOutputInterface);
        $this->assertEquals($model->getName()->getValue(), self::$sampleName);
        $this->assertEquals($model->getStatus()->getValue(), self::$sampleStatus);
    }
}
