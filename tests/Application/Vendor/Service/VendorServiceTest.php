<?php

namespace DoSystemTest\Application\Vendor\Service;

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
    private $showService;

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
        $this->showService = doSystem()->make(GetVendorService::class);
    }

    /**
     * @test
     *
     * @return VendorValueId
     */
    public function testCreateVendor()
    {
        $statusValues = VendorValueStatus::values();
        $key = \rand(0, count($statusValues) - 1);
        for ($i = 0; $i < count($statusValues); $i++) {
            $statusValue = \array_shift($statusValues);
            if ($i === $key) {
                self::$sampleStatus = $statusValue->getValue();
            }
        }

        $input = doSystem()->makeWith(CreateVendorInputInterface::class, [
            'id' => null,
            'name' => self::$sampleName,
            'status' => self::$sampleStatus,
        ]);
        $id = $this->createService->handle($input);

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
        $model = $this->showService->handle($id);

        $this->assertTrue($model instanceof GetVendorOutputInterface);
        $this->assertEquals($model->getName()->getValue(), self::$sampleName);
        $this->assertEquals($model->getStatus()->getValue(), self::$sampleStatus);
    }
}
