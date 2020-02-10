<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
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
            'status' => 3,
        ],
        // Id will be 2
        [
            'name' => 'Workstore',
            'status' => 0
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
            $status = VendorValueStatus::of($args['status']);
            $entity = new Vendor(null, $name, $status);
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
