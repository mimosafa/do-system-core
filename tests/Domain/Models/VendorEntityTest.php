<?php

namespace Tests\Domain\Models;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Models\Vendor\Vendor;
use DoSystem\Domain\Models\Vendor\VendorValueId;
use DoSystem\Domain\Models\Vendor\VendorValueName;
use DoSystem\Domain\Models\Vendor\VendorRepositoryInterface;
use DoSystem\InMemory\Repositories\VendorRepository;

class VendorEntityTest extends TestCase
{
    /**
     * @var VendorRepositoryInterface
     */
    protected $repository;

    /**
     * Mock data
     */
    protected $data = [
        // Id will be 1
        [
            'name' => 'TokyoDo',
        ],
        // Id will be 2
        [
            'name' => 'Workstore',
        ],
    ];

    /**
     * Cache of created entity's id
     *
     * @var VendorValueId[]
     */
    protected $ids = [];

    protected function setUp(): void
    {
        $this->repository = new VendorRepository();

        foreach ($this->data as $args) {
            $name = VendorValueName::of($args['name']);
            $entity = new Vendor(null, $name);
            $this->ids[] = $this->repository->store($entity);
        }
    }

    /**
     * @test
     */
    public function InMemoryVendorRepository()
    {
        $id1 = $this->ids[0];
        $this->assertTrue($id1 instanceof VendorValueId);

        $entity1 = $this->repository->findById($id1);
        $this->assertTrue($entity1 instanceof Vendor);
    }

    /**
     * @test
     */
    public function VendorValueId()
    {
        $id1 = $this->ids[0];
        $entity1 = $this->repository->findById($id1);

        $id2 = $this->ids[1];
        $entity2 = $this->repository->findById($id2);

        // VendorValueId
        $this->assertTrue($id1->equals($entity1->getId()));
        $this->assertFalse($id1->equals($entity2->getId()));
    }

    /**
     * @test
     */
    public function VendorValueName()
    {
        $id1 = $this->ids[0];
        $entity1 = $this->repository->findById($id1);
        $name1 = $entity1->getName();

        $id2 = $this->ids[1];
        $entity2 = $this->repository->findById($id2);
        $name2 = $entity2->getName();

        // VendorValueName
        $this->assertEquals($name1->getValue(), $this->data[0]['name']);
    }
}
