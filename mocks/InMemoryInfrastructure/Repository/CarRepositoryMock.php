<?php

namespace DoSystemMock\InMemoryInfrastructure\Repository;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Exception\NotFoundException;

class CarRepositoryMock implements CarRepositoryInterface
{
    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * @var array
     */
    private $db = [
        // [ 'id' => 1, 'vendor_id' => 1, 'vin' => '品川500さ2345', 'name' => 'Test Car'],
        // [ 'id' => 2, 'vendor_id' => 1, 'vin' => '多摩500さ4649', 'name' => 'DeLorean'],
        // [ 'id' => 3, 'vendor_id' => 2, 'vin' => '京都500あ4649', 'name' => 'Benz'],
    ];

    /**
     * @var int
     */
    private $lastId = 0;

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

        if ($maybeId->exists()) {
            $id = $maybeId->getValue();
            $ids = \array_column($this->db, 'id');
            if (!$i = \array_search($id, $ids, true)) {
                throw new NotFoundException('Not Found');
            }
            $row =& $this->db[$i];
        }
        else {
            $id = ++$this->lastId;
            $this->db[] = ['id' => $id];
            $row =& $this->db[count($this->db) - 1];
        }

        $row['vendor_id'] = $vendor->getId()->getValue();
        $row['vin'] = $vin->getValue();
        $row['name'] = $name->getValue();

        return CarValueId::of($id);
    }

    /**
     * @param CarValueId $id
     * @return Car
     * @throws NotFoundException
     */
    public function findById(CarValueId $id): Car
    {
        /** @var int */
        $int = $id->getValue();

        $ids = \array_column($this->db, 'id');
        $row = $this->db[\array_search($int, $ids, true)] ?? null;

        if (!isset($row)) {
            throw new NotFoundException('Not found');
        }

        $id = CarValueId::of($int);
        $vendor = $this->vendorRepository->findById(VendorValueId::of($row['vendor_id']));
        $vin = CarValueVin::of($row['vin']);
        $name = CarValueName::of($row['name']);

        return new Car($id, $vendor, $vin, $name);
    }

    /**
     * @param array $params
     * @return CarCollection
     */
    public function query(array $params): CarCollection
    {
        $result = $this->db;

        if (!empty($result)) {
            $vendorFilter = Arr::pull($params, 'vendor_id');
            $vinFilter = Arr::pull($params, 'vin');

            $result = Arr::where($result, function ($row) use ($vendorFilter, $vinFilter) {
                if ($vendorFilter && !\in_array($row['vendor_id'], $vendorFilter, true)) {
                    return false;
                }
                if ($vinFilter && !Str::contains($row['vin'], $vinFilter)) {
                    return false;
                }
                return true;
            });
        }

        if (!empty($result) && $size = Arr::pull($params, 'size_per_page')) {
            $page = Arr::pull($params, 'page');
            $start = ($page - 1) * $size;
            $result = \array_slice($result, $start, $size);
        }

        if (!empty($result)) {
            $result = \array_map(function ($row) {
                $id = CarValueId::of($row['id']);
                $vendor = $this->vendorRepository->findById(VendorValueId::of($row['vendor_id']));
                $vin = CarValueVin::of($row['vin']);
                $name = CarValueName::of($row['name']);
                return new Car($id, $vendor, $vin, $name);
            }, $result);
        }

        return new CarCollection($result);
    }

    /**
     * Flush $db & $lastId for testing
     */
    public function flush()
    {
        $this->db = [];
        $this->lastId = 0;
    }
}
