<?php

namespace Tests\UseCases;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Service\CreateVendorEntity;
use DoSystem\InMemory\Repositories\VendorRepository; /** @todo DI */

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

        $id = $this->service->handle(VendorValueName::of($name), VendorValueStatus::of($status));

        $this->assertTrue($id instanceof VendorValueId);
    }

    /**
     * @test
     */
    public function testHandleIfStatusIsNull()
    {
        $name = 'TokyoDo';

        $id = $this->service->handle(VendorValueName::of($name));

        $this->assertTrue($id instanceof VendorValueId);
    }
}
