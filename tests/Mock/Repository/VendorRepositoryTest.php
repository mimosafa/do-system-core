<?php

namespace Tests\Domain\Repository;

use DoSystem\Domain\Models\Vendor\Vendor;
use DoSystem\Domain\Models\Vendor\VendorValueName;
use DoSystem\InMemory\Repositories\VendorRepository;
use PHPUnit\Framework\TestCase;

/**
 * @group mock
 * @group vendor-model
 */
class VendorRepositoryTest extends TestCase
{
    /**
     * @var VendorRepository
     */
    protected $repository;

    protected function setUp(): void
    {
        $this->repository = new VendorRepository();
    }

    /**
     * @test
     */
    public function testRepository()
    {
        $name = 'Toshi';

        $newModel = new Vendor(null, new VendorValueName($name));

        $id = $this->repository->store($newModel);

        $model = $this->repository->find($id);

        $this->assertEquals($name, $model->getName()->getValue());
    }
}
