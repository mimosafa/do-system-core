<?php

namespace DoSystemMock\Infrastructure\Seeder;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystemMock\Factory\VendorDataFactory;

class VendorsSeeder
{
    /**
     * @var int
     */
    private $numberOfRows;

    /**
     * @var array[]
     */
    private $fakeData = [];

    /**
     * @var bool
     */
    private $done = false;

    /**
     * Constructor
     *
     * @param int $numberOfRows
     */
    public function __construct(int $numberOfRows)
    {
        $this->numberOfRows = $numberOfRows;
    }

    /**
     * @param VendorRepositoryInterface $repository
     * @return void
     */
    public function seed(VendorRepositoryInterface $repository): void
    {
        if ($this->done) {
            return;
        }

        for ($i = 0; $i < $this->numberOfRows; $i++) {
            $data = VendorDataFactory::generate();
            $model = new Vendor(
                VendorValueId::of(null),
                VendorValueName::of($data['name']),
                VendorValueStatus::of($data['status'])
            );
            $id = $repository->store($model);
            $data['id'] = $id->getValue();
            $this->fakeData[] = $data;
        }

        $this->done = true;
    }

    /**
     * @return array[]
     */
    public function getData(): array
    {
        return $this->fakeData;
    }
}
