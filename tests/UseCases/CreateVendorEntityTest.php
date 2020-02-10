<?php

namespace Tests\UseCases;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Service\CreateEntity;
use DoSystem\InMemory\Repositories\VendorRepository;

class CreateVendorEntityTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        $repository = new VendorRepository();
        $this->service = new CreateEntity($repository);
    }
    public function testHandle()
    {
        $name = 'Toshi';
        $status = 0;

        $entity = $this->service->handle($name, $status);

        $this->assertTrue($entity instanceof Vendor);
        $this->assertEquals($name, $entity->getName()->getValue());
    }
}
