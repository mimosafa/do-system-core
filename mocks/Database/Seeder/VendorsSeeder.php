<?php

namespace DoSystemCoreMock\Database\Seeder;

use DoSystem\Core\Domain\Vendor\Vendor;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorValueName;
use DoSystem\Core\Domain\Vendor\VendorValueStatus;
use DoSystemCoreMock\Database\Factory\VendorDataFactory;

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
