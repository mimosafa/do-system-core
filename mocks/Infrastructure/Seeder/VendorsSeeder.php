<?php

namespace DoSystemMock\Infrastructure\Seeder;

use DoSystemMock\Factory\VendorDataFactory;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

class VendorsSeeder
{
    /**
     * @var int
     */
    private $numberOfVendors;

    /**
     * @var array[]
     */
    private $fakeData = [];

    /**
     * @var VendorRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param int $numberOfVendors
     */
    public function __construct(int $numberOfVendors)
    {
        $this->numberOfVendors = $numberOfVendors;
        $this->prepareData();
    }

    /**
     * @param VendorRepositoryInterface $repository
     * @return void
     */
    public function seed(VendorRepositoryInterface $repository): void
    {
        $this->repository = $repository;

        foreach ($this->fakeData as &$data) {
            $model = new Vendor(
                VendorValueId::of(null),
                VendorValueName::of($data['name']),
                VendorValueStatus::of($data['status'])
            );
            $id = $this->repository->store($model);
            $data['id'] = $id->getValue();
        }
    }

    /**
     * @return array[]
     */
    public function getData(): array
    {
        return $this->fakeData;
    }

    /**
     * @return void
     */
    private function prepareData(): void
    {
        for ($i = 0; $i < $this->numberOfVendors; $i++) {
            $this->fakeData[] = VendorDataFactory::generate();
        }
    }
}
