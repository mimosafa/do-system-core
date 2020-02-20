<?php

namespace DoSystemMock\InMemoryInfrastructure\Repository;

use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\InMemory\Repositories\VendorRepository;
use DoSystem\Exception\NotFoundException;

class CarRepositoryMock implements CarRepositoryInterface
{
    /**
     * @var array
     */
    private $db = [];

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param Car $model
     * @return CarValueId
     * @throws NotFoundException
     */
    public function store(Car $model): CarValueId
    {
        /** @var CarValueId */
        $maybeId = $model->getId();

        /** @var Vendor */
        $vendor = $model->belongsTo();

        /** @var CarValueVin */
        $vin = $model->getVin();

        /** @var CarValueName */
        $name = $model->getName();

        if (!$maybeId->exists()) {
            $idValue = count($this->db) + 1;
            $this->db[$idValue] = [];
        } else {
            $idValue = $maybeId->getValue();
            if (!isset($this->db[$idValue])) {
                throw new NotFoundException('Not Found');
            }
        }

        $row =& $this->db[$idValue];

        $row['vendor_id'] = $vendor->getId()->getValue();
        $row['vin'] = $vin->getValue();
        $row['name'] = $name->getValue();

        return CarValueId::of($idValue);
    }

    /**
     * @param CarValueId $id
     * @return Car
     * @throws NotFoundException
     */
    public function findById(CarValueId $id): Car
    {
        $idValue = $id->getValue();

        if (!isset($this->db[$idValue])) {
            throw new NotFoundException('Not found');
        }

        $row = $this->db[$idValue];

        $id = CarValueId::of($idValue);
        $vendor = $this->vendorRepository->findById(VendorValueId::of($row['vendor_id']));
        $vin = CarValueVin::of($row['vin']);
        $name = CarValueName::of($row['name']);

        return new Car($id, $vendor, $vin, $name);
    }

    /**
     * @param array $params
     * @return CarCollection
     */
    public function find(array $params = []): CarCollection
    {
        //

        $array = [];
        return new CarCollection($array);
    }
}
