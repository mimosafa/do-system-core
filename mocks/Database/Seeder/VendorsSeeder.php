<?php

namespace DoSystemMock\Database\Seeder;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystemMock\Database\Factory\VendorDataFactory;

class VendorsSeeder
{
    use SeederTrait;

    /**
     * Number of data
     *
     * @var int
     */
    private $num;

    /**
     * Constructor
     *
     * @param int $num
     * @throws \Exception
     */
    public function __construct(int $num)
    {
        if ($num < 1) {
            throw new \Exception('Parameter of ' . __METHOD__ . ' must be positive integer.');
        }
        $this->num = $num;
    }

    /**
     * Seed data to repository
     *
     * @param VendorRepositoryInterface $repository
     * @return self|null
     */
    public function seed(VendorRepositoryInterface $repository): ?self
    {
        if (!empty($this->data)) {
            return null;
        }

        for ($i = 0; $i < $this->num; $i++) {
            $data = VendorDataFactory::generate();
            $model = new Vendor(
                VendorValueId::of(null),
                VendorValueName::of($data['name']),
                VendorValueStatus::of($data['status'])
            );
            $data['id'] = $repository->store($model)->getValue();
            $this->data[] = $data;
        }

        return $this;
    }
}
