<?php

namespace Tests\UseCases;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Service\CreateEntity;
use DoSystem\InMemory\Repositories\VendorRepository;

class CreateVendorEntityTest extends TestCase
{
    public function testHandle()
    {
        $name = 'Toshi';
        $service = new CreateEntity(new VendorRepository());
        $entity = $service->handle($name);

        $this->assertTrue($entity instanceof Vendor);
        $this->assertEquals($name, $entity->getName()->getValue());
    }
}
