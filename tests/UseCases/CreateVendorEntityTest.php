<?php

namespace Tests\UseCases;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Service\CreateVendorEntity;
use DoSystem\InMemory\Repositories\VendorRepository;

class CreateVendorEntityTest extends TestCase
{
    /**
     * @var CreateVendorEntity
     */
    protected $service;

    protected function setUp(): void
    {
        $repository = new VendorRepository();
        $this->service = new CreateVendorEntity($repository);
    }

    /**
     * @test
     */
    public function testHandle()
    {
        $name = 'Toshi';
        $status = 0;

        $entity = $this->service->handle(VendorValueName::of($name), VendorValueStatus::of($status));

        $this->assertTrue($entity instanceof Vendor);
        $this->assertEquals($name, $entity->getName()->getValue());
    }

    /**
     * @test
     */
    public function testHandleIfStatusIsNull()
    {
        $name = 'TokyoDo';

        $entity = $this->service->handle(VendorValueName::of($name));

        $this->assertTrue($entity instanceof Vendor);
        $this->assertTrue($entity->getStatus()->equals(VendorValueStatus::defaultStatus()));
    }
}
